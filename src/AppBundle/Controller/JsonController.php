<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\PastTimeline;

/**
 * @Route("/json")
 * @Method({"GET"})
 */
class JsonController extends Controller
{
  /**
  * @Route("/daily/{date}", requirements={"date" = "\d{4}-\d{2}-\d{2}"}, defaults={"date" = "0000-00-00"}, name="json_daily")
  */
  public function dailyAction($date)
  {
     // 今日のタイムラインを返す
     if ($date === (new \DateTime())->format('Y-m-d')) {
       $twitterApi = $this->container->get('twitter_api');
       $timeline = $twitterApi->getTodayTimeline();

       return new JsonResponse($timeline);
     }

     // DBから過去日のタイムラインを取得
     $repository =$this->getDoctrine()->getRepository('AppBundle:PastTimeline');
     $pastTimeline = $repository->findOneBy([
       'user' => $this->get('security.token_storage')->getToken()->getUser(),
       'date' => new \DateTime($date)
     ]);

     $timelinejson = !is_null($pastTimeline) ? json_decode($pastTimeline->getTimelineJson(), true) : [];

     $timelinejson = json_encode($this->get('app.service.common_service')->enableHtmlLink($timelinejson));

     // DBに入れる際、既にjson_encode済みなので通常のResponseクラスを使う
     $response = new Response($timelinejson);
     $response->headers->set('Content-Type', 'application/json');
     return $response;
  }
}
