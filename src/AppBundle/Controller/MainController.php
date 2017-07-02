<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

class MainController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     */
    public function indexAction()
    {
        return $this->render(':main:index.html.twig');
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
        dump($this->fetchTimeline($user));

        return $this->render(
            ':main:home.html.twig',
            [
                'props' => $this->get('jms_serializer')->serialize(
                    [
                        'timelineJson' => $this->fetchTimeline($user),
                        'username' => $user->getUsername(),
                        'isLogin' => $this->isGranted('ROLE_OAUTH_USER'),
                        'isShowImportModal' => $this->isShowImportModal(),
                        'isInitialImportDebug' => $this->isInitialImportDebug(),
                        'transText' => $this->getHomeTransText(),
                    ],
                    'json'
                ),
            ]
        );
    }

    /**
     * @param User $user
     *
     * @return array [0 => [json], 1 => [json]]
     */
    private function fetchTimeline(User $user): array
    {
        $res = [];
        $res[] = [$this->get('app.service.common_service')->getToday() => $this->fetchTodayTimeline($user)];

        $repository = $this->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:PastTimeline');
        $pastTimelines = $repository->findBy(
            [
                'user' => $user,
            ],
            [
                'date' => 'DESC',
            ],
            30
        );

        if (count($pastTimelines)) {
            foreach ($pastTimelines as $item) {
                $res[] = [$item->getDate()->format('Y-m-d') => $item->getTimeline()];
            }
        }

        return $res;
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
        array_unshift($timelineDateList, $this->get('app.service.common_service')->getToday());

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

    private function getHomeTransText(): array
    {
        $kernelDir = $this->get('kernel')->getRootDir();
        $locale = $this->get('request')->getLocale();
        $file = $kernelDir.'/Resources/translations/messages.'.$locale.'.yml';

        if (!file_exists($file)) {
            $file = $kernelDir.'/Resources/translations/messages.en.yml';
        }

        $parsed = Yaml::parse(file_get_contents($file));

        return $parsed['home'];
    }
}
