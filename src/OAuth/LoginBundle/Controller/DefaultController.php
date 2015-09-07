<?php

namespace OAuth\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
    * @Route("/", name="indexpage")
    */
    public function indexAction()
    {
        return $this->render('OAuthLoginBundle:Default:index.html.twig');
    }

      /**
      * @Route("/login", name="loginpage")
      */
    public function loginAction()
    {
        return $this->render('OAuthLoginBundle:Default:login.html.twig');
    }
}
