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
        //dump($this->get('security.token_storage')->getToken());
        $twitterApi = $this->container->get('twitter_api');

        // 今日のtimelineを取得
        $timeline = $twitterApi->getTodayTimeline();
        // DBから過去のtimelinelistを取得
        $pastTimelines = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser($this->get('security.token_storage')->getToken()->getUser());
        $past_timeline_date_list = array_map(function($obj) {return $obj->getDate()->format('Y-m-d');} , $pastTimelines);
        dump($past_timeline_date_list);
        return $this->render('AppBundle:Default:index.html.twig',[
          'timeline' => $timeline,
          'past_timeline_date_list' => $past_timeline_date_list,
        ]);
    }

    /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render('AppBundle:Default:login.html.twig');
   }

   /**
   *
   * @Route("/pastday.json/{date}", requirements={"date" = "\d{4}-\d{2}-\d{2}"}, defaults={"date" = "0000-00-00"}, name="pastday_json")
   */
   public function pastDayJsonAction($date)
   {
      $repository =$this->getDoctrine()->getRepository('AppBundle:PastTimeline');
      $pastTimeline = $repository->findOneBy(array(
        'user' => $this->get('security.token_storage')->getToken()->getUser(),
        'date' => new \DateTime($date)
      ));

      $response = new Response($pastTimeline->getTimelineJson());
      $response->headers->set('Content-Type', 'application/json');
      return $response;
   }
}
