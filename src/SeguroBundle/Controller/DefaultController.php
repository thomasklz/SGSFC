<?php

namespace SeguroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="user_login")
     */
    public function indexAction()
    {
        return $this->render('SeguroBundle:Usuario:login.html.twig');
    }

 }