<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PastTimeline;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction(Request $request)
    {
        $twitterApi = $this->container->get('twitter_api');
        $twitterApi->setUser($this->getUser());

        // 今日のtimelineを取得
        $timeline = $twitterApi->getTodayTimeline();
        $timeline = $this->get('app.service.common_service')->enableHtmlLink($timeline);

        // DBから過去のtimelinelistを取得
        // TODO: 長いのでもっと簡単にUserオブジェクトを取得する手段を考える
        $pastTimelines = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser($this->get('security.token_storage')->getToken()->getUser(), ['date' => 'DESC']);
        $timeline_date_list = array_map(function ($obj) {
            return $obj->getDate()->format('Y-m-d');
        }, $pastTimelines);
        // 今日のタイムライン表示ボタンに使用
        array_unshift($timeline_date_list, (new \DateTime())->format('Y-m-d'));

        return $this->render(':default:index.html.twig', [
            'props' => $this->get('jms_serializer')->serialize(
                [
                    'json_daily_url' => $this->generateUrl('json_daily'),
                    'timeline_date_list' => $timeline_date_list,
                    'timeline_json' => $timeline,
                    'app_user_username' => $this->getUser()->getUsername(),
                ], 'json'),
        ]);
    }

   /**
    * @Route("/login", name="login")
    */
   public function loginAction()
   {
       return $this->render(':default:login.html.twig');
   }
}
