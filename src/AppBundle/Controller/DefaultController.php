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
        $timeline = $twitterApi->findIdRangeByDate(new \DateTime('yesterday'));
        //$dbuser = $this->getDoctrine()->getRepository('AppBundle:User')->find($user->getId());
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->merge($user);
        $pastTimeline = new PastTimeline();
        $pastTimeline->setUser($user);
        $pastTimeline->setDate(new \DateTime('yesterday'));
        $pastTimeline->setTimelineJson(json_encode($pastTimeline));
        $pastTimeline->setCreateAt(new \DateTime());
        $pastTimeline->setUpdateAt(new \DateTime());
        $em->persist($user);
        $em->persist($pastTimeline);
        $em->flush();

        // DBから過去のタイムラインを取得
        //$timeline = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->find(2)->getTimelineJson();

        //今日のつぶやき一覧をtemplateに貼り付けてrender
        return $this->render('AppBundle:Default:index.html.twig', array('timeline' => $timeline['timeline_json']));
    }

    /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render('AppBundle:Default:login.html.twig');
   }
}
