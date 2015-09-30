<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\PastTimeline;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction(Request $request)
    {
        $twitterApi = $this->container->get('twitter_api');

        $testline = $twitterApi->findIdRangeByDate(new \DateTime('2015-09-24'));
        dump($testline);

        // 今日のtimelineを取得
        $timeline = $twitterApi->getTodayTimeline();

        // DBから過去のtimelinelistを取得
        $pastTimelines = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser($this->get('security.token_storage')->getToken()->getUser(), ['date' => 'DESC']);
        $timeline_date_list = array_map(function($obj) {return $obj->getDate()->format('Y-m-d');} , $pastTimelines);
        // 今日のタイムラインを表示するボタンに使用
        array_unshift($timeline_date_list, (new \DateTime())->format('Y-m-d'));

        return $this->render('AppBundle:Default:index.html.twig',[
          'timeline' => $timeline,
          'timeline_date_list' => $timeline_date_list,
        ]);
    }

    /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render('AppBundle:Default:login.html.twig');
   }
}
