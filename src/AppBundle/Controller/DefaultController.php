<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction()
    {
        return $this->render(':default:index.html.twig');
    }

    /**
     * @Route("/{username}", name="home")
     * @ParamConverter("user", options={"mapping": {"username": "username"}})
     */
    public function homeAction(User $user = null)
    {
        if (is_null($user)) {
            return $this->redirectToRoute('indexpage');
        }

        return $this->render(
            ':default:home.html.twig',
            [
                'props' => $this->get('jms_serializer')->serialize(
                    [
                        'timelineDateList' => $this->fetchPastTimelineDate($user),
                        'timelineJson' => $this->fetchTodayTimeline($user),
                        'username' => $user->getUsername(),
                        'isLogin' => $this->isGranted('ROLE_OAUTH_USER'),
                        'isShowImportModal' => $this->isShowImportModal(),
                        'isInitialImportDebug' => $this->isInitialImportDebug(),
                    ],
                    'json'
                ),
            ]
        );
    }

    private function fetchTodayTimeline(User $user): array
    {
        $twitterApi = $this->container->get('twitter_api');
        $twitterApi->setUser($user);

        $timeline = $twitterApi->getTodayTimeline();
        if (!isset($timeline['error'])) {
            $timeline = $this->get('app.service.common_service')->enableHtmlLink($timeline);
        }

        return $timeline;
    }

    private function fetchPastTimelineDate(User $user): array
    {
        $pastTimelines = $this->getDoctrine()->getRepository('AppBundle:PastTimeline')->findByUser(
            $user,
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

    private function isShowImportModal(): bool
    {
        // only develop
        if ($this->isInitialImportDebug()) {
            return true;
        }

        // not login
        if (!$this->isGranted('ROLE_OAUTH_USER')) {
            return false;
        }

        $repository = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:User');
        $user = $repository->find($this->getUser()->getId());

        // already import
        if ($user->getIsInitialTweetImport()) {
            return false;
        }

        return true;
    }

    private function isInitialImportDebug(): bool
    {
        if ($this->getParameter('initial_import_debug') === true) {
            return true;
        }

        return false;
    }
}
