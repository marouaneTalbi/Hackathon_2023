<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/accueil', name: 'content_index')]
    public function index(): Response
    {
        return $this->render('back/content/index.html.twig');
    }

    #[Route('/{type}/choose-content', name: 'choose_content')]
    public function becomeNovice(Request $request,UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if($request->get('type') == "novice") {
            $user->setRoles(['ROLE_NOVICE']);
        } else if($request->get('type') == "pro") {
            $user->setRoles(['ROLE_PRO']);
        } else {
            throw $this->createNotFoundException('Le button est invalide');
        }

        $userRepository->save($user, true);

        return $this->render('back/content/choose.html.twig');
    }

}