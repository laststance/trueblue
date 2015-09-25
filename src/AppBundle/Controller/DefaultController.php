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

        $user = $this->get('security.token_storage')->getToken()->getUser(); //->getId();
        //$past_time_lime = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser($user);

        //昨日のつぶやき一覧を取得
        $timeline = $twitterApi->getTodayTimeline();

        //$dbuser = $this->getDoctrine()->getRepository('AppBundle:User')->find($user->getId());

        // DBから過去のタイムラインを取得
        //$timeline = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->find(2)->getTimelineJson();

        //今日のつぶやき一覧をtemplateに貼り付けてrender
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
