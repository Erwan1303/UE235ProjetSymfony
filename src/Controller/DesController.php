<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class DesController extends AbstractController
{
    /**
     * @Route("/football", name="football")
     */
    public function football()
    {
        return $this->render('descategory/football.html.twig');
    }

    /**
     * @Route("/basketball", name="basketball")
     */
    public function basketball()
    {
        return $this->render('descategory/basketball.html.twig');
    }

    /**
     * @Route("/rugby", name="rugby")
     */
    public function rugby()
    {
        return $this->render('descategory/rugby.html.twig');
    }
}
