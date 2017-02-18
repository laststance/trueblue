<?php

namespace AppBundle\Command;

use AppBundle\Entity\PastTimeline;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 指定した日付の全ユーザーのタイムラインをDBに保存する.
 *
 * app/console cron:SaveTargetDateTimeline yyyy-mm-dd
 */
class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:SaveTargetDateTimeline')
            ->setDescription('Save to DB All users Target Date Timeline.')
            ->addArgument(
                'date',
                InputArgument::REQUIRED,
                'what date do you want to save?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // 引数のフォーマットバリデーション
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $argDate = $input->getArgument('date'))) {
            throw new \InvalidArgumentException('invalid argument. date format must be yyyy-mm-dd. e.g. 2020-04-03');
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        // 登録ユーザーを取得
        $users = $doctrine->getRepository('AppBundle:User')->findAll();
        $twitterApi = $this->getContainer()->get('twitter_api');
        // タイムラインを取得する日付
        $targetDate = new \DateTime($argDate);

        // メイン処理
        foreach ($users as $user) {
            $twitterApi->setUser($user);

            // 昨日のタイムラインJsonを取得
            $res = $twitterApi->findIdRangeByDate($targetDate);

            // 指定日のタイムラインが一件も無い場合
            if (!array_key_exists('timeline_json', $res)) {
                continue;
            }

            $encodedJson = json_encode($res['timeline_json']); // DBにはJSONとして格納する

            $em->getRepository('AppBundle:PastTimeline')->insert(
                $user,
                $encodedJson,
                $targetDate
            );
        }

        $output->writeln('Save PastTimeLine Complete.');
    }
}
