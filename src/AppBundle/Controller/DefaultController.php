<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PastTimeline;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction(Request $request)
    {
        dump($this->get('security.token_storage')->getToken());
        $twitterApi = $this->container->get('twitter_api');

        // 今日のtimelineを取得
        $timeline = $twitterApi->getTodayTimeline();
        // DBから過去のtimelinelistを取得
        $past_timeline_list = $twitterApi->getPastTimelineList();

        return $this->render('AppBundle:Default:index.html.twig', ['timeline' => $timeline]);
    }

    /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render('AppBundle:Default:login.html.twig');
   }
}
