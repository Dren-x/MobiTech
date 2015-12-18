<?php

namespace Andrey\crudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AndreycrudBundle:Default:index.html.twig', array('name' => $name));
    }
}
