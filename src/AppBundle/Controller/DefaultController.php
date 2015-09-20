<?php

namespace AppBundle\Controller;

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
        $twitter_api = $this->container->get('twitter_api');
        // dump($this->get('security.token_storage')->getToken());
        dump($twitter_api);
        //今日のつぶやき一覧を取得
        $today_tweet = '';
        //今日のつぶやき一覧をtemplateに貼り付けてrender
        return $this->render('AppBundle:Default:index.html.twig', array('today_tweet' => $today_tweet));
    }
}
