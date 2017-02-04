<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction()
    {
        return $this->render(
            ':default:index.html.twig',
            [
                'props' => $this->get('jms_serializer')->serialize(
                    [
                        'timelineDateList' => $this->fetchPastTimelineDate(),
                        'timelineJson' => $this->fetchTodayTimeline(),
                        'appUsername' => $this->getUser()->getUsername(),
                    ],
                    'json'
                ),
            ]
        );
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        return $this->render(':default:login.html.twig');
    }

    /**
     * @return array
     */
    private function fetchTodayTimeline(): array
    {
        $twitterApi = $this->container->get('twitter_api');
        $twitterApi->setUser($this->getUser());

        $timeline = $twitterApi->getTodayTimeline();
        if (!isset($timeline['error'])) {
            $timeline = $this->get('app.service.common_service')->enableHtmlLink($timeline);
        }

        return $timeline;
    }

    /**
     * @return array
     */
    private function fetchPastTimelineDate(): array
    {
        $pastTimelines = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser(
            $this->getUser(),
            ['date' => 'DESC']
        );
        $timelineDateList = array_map(
            function ($obj) {
                return $obj->getDate()->format('Y-m-d');
            },
            $pastTimelines
        );
        // 今日のタイムライン表示ボタンに使用
        array_unshift($timelineDateList, (new \DateTime())->format('Y-m-d'));

        return $timelineDateList;
    }
}
