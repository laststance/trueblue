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
        dump(get_class($this->get('security.token_storage')->getToken()));
        return $this->render('AppBundle:Default:index.html.twig');
    }
}
