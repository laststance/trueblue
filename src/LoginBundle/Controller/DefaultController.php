<?php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

      /**
      * @Route("/login", name="login")
      */
    public function loginAction()
    {
        return $this->render('LoginBundle:Default:login.html.twig');
    }
}
