<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Service\TwitterAPI;

class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:SaveYesterdayTimeline');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $twitterApi =
        $doctrine = $this->getContainer()->get('doctrine');
        // 登録ユーザーを取得
        $users = $doctrine->getRepository('AppBundle:User')->findAll();
        print_r($users);
        // 昨日のタイムラインを取得

        //$output->writeln($text);
    }
}
