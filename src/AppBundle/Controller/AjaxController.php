<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PastTimeline;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/ajax")
 * @Method({"GET"})
 */
class AjaxController extends Controller
{
    /**
     * @Route("/{username}/{date}", requirements={"date" = "\d{4}-\d{2}-\d{2}"}, defaults={"date" = "0000-00-00"}, name="json_daily")
     * @ParamConverter("user", options={"mapping": {"username": "username"}})
     */
    public function dailyAction($date, User $user)
    {
        // 今日のタイムラインを返す
        if ($date === (new \DateTime())->format('Y-m-d')) {
            $twitterApi = $this->container->get('twitter_api');
            $twitterApi->setUser($user);
            $timeline = $twitterApi->getTodayTimeline();

            return new JsonResponse($timeline);
        }

        // DBから過去日のタイムラインを取得
        $repository = $this->getDoctrine()->getRepository('AppBundle:PastTimeline');
        $pastTimeline = $repository->findOneBy(
            [
                'user' => $user,
                'date' => new \DateTime($date),
            ]
        );

        $timelinejson = !is_null($pastTimeline) ? json_decode($pastTimeline->getTimelineJson(), true) : [];

        $timelinejson = json_encode($this->get('app.service.common_service')->enableHtmlLink($timelinejson));

        // DBに入れる際、既にjson_encode済みなので通常のResponseクラスを使う
        $response = new Response($timelinejson);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Security("has_role('ROLE_OAUTH_USER')")
     * @Route("/initial/import", name="initial_import")
     */
    public function initialImportAction()
    {
        $complateMessage = 'complate';
        $entityManager = $this->get('doctrine.orm.default_entity_manager');
        $user = $entityManager->getRepository('AppBundle:User')->find($this->getUser()->getId());
        $this->get('twitter_api')->setUser($user);

        if ($user->getIsInitialTweetImport()) {
            return new JsonResponse('already imported');
        }

        try {
            $dates = array_map(function ($d) {
                return new \DateTime($d.' days ago');
            }, range(1, 14));
            foreach ($dates as $d) {
                if ($entityManager->getRepository('AppBundle:PastTimeline')->findOneBy(['date' => $d]) instanceof PastTimeline) {
                    continue;
                }

                $json = $this->get('twitter_api')->findIdRangeByDate($d);

                if (isset($json['error'])) {
                    if ($json['error'] == 'timeline get count 0.') {
                        return new JsonResponse($complateMessage);
                    }

                    if ($json['error'] == 'target days tweet not found.') {
                        continue;
                    }
                }

                $entityManager->getRepository('AppBundle:PastTimeline')
                    ->insert(
                        $user,
                        json_encode($json['timeline_json']),
                        $d
                    );
            }
        } catch (\Exception $e) {
            throw new \LogicException('faild import', $e->getCode(), $e);
        }

        $user->setIsInitialTweetImport(true);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($complateMessage);
    }
}
