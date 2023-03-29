<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/accueil', name: 'content_index')]
    public function index(): Response
    {
        return $this->render('back/content/index.html.twig');
    }

    #[Route('/choose-content', name: 'content_choose')]
    public function chooseContent(): Response
    {
        return $this->render('back/content/choose.html.twig');
    }

}