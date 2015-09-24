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
        $twitterApi = $this->container->get('twitter_api');
        dump($this->get('security.token_storage')->getToken());
        //今日のつぶやき一覧を取得
        //$timeline = $twitterApi->getTodayTimeline();

        // DBから過去のタイムラインを取得してreactに渡す
        $timeline = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->find(2)->getTimelineJson();
        $timeline = json_decode($timeline);
        dump($timeline);

        //今日のつぶやき一覧をtemplateに貼り付けてrender
        return $this->render('AppBundle:Default:index.html.twig', array('timeline' => $timeline));
    }

    /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render('AppBundle:Default:login.html.twig');
   }
}
