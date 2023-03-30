<?php

namespace App\Controller\Front;

use App\Entity\Status;
use App\Entity\User;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/status', name: 'app_status_')]
class StatusController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(StatusRepository $statusRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
        return $this->render('front/status/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/define', name: 'create')]
    public function define(Request $request, StatusRepository $statusRepository): Response
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status->setCreatedAt(new DateTime());
            $status->addClient($this->getUser());
            $statusRepository->save($status, true);
            return $this->redirectToRoute('front_app_status_index');
        }
        return $this->render('front/status/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
