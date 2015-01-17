<?php

namespace TngWorkshop\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TngWorkshopBoardBundle:Default:index.html.php');
    }
}
