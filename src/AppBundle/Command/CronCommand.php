<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\TwitterAPI;
use AppBundle\Entity\User;
use AppBundle\Entity\PastTimeline;

/**
 * 指定した日付の全ユーザーのタイムラインをDBに保存する
 *
 * TODO: テストが出来ないのでTwitterAPIをDIしたい
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
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $arg_date = $input->getArgument('date'))) {
            throw new \InvalidArgumentException('invalid argument. date format must be yyyy-mm-dd. e.g. 2020-04-03');
        }

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        // 登録ユーザーを取得
        $users = $doctrine->getRepository('AppBundle:User')->findAll();
        $client = $this->getContainer()->get('app.service.httpclient');
        // tiwtter_apiのアクセストークン類
        $api_parameter = $this->getContainer()->getParameter('twitter_api');
        $commonService = $this->getContainer()->get('app.service.common_service');
        // タイムラインを取得する日付
        $targetDate = new \DateTime($arg_date);

        // メイン処理
        foreach ($users as $user) {
            if (empty($twitterApi)) {
                $twitterApi = new twitterApi($doctrine, $user, $client, $api_parameter, $commonService);
            } else {
                $twitterApi->setUser($user);
            }
            // 昨日のタイムラインJsonを取得
            $res = $twitterApi->findIdRangeByDate($targetDate);

            // 指定日のタイムラインが一件も無い場合
            if (!array_key_exists('timeline_json', $res)) {
                continue;
            }

            $encoded_json = json_encode($res['timeline_json']); // DBにはJSONとして格納する

            // DBに保存するTimeLineオブジェクトを作成
            $pastTimeLine = new PastTimeline();
            $pastTimeLine->setUser($user);
            $pastTimeLine->setTimelineJson($encoded_json);
            $pastTimeLine->setDate($targetDate);
            $now = new \DateTime();
            $pastTimeLine->setCreateAt($now);
            $pastTimeLine->setUpdateAt($now);

            // DB保存処理
            $em->persist($pastTimeLine);
            $em->flush();
        }

        $output->writeln('Save PastTimeLine Complete.');
    }
}
