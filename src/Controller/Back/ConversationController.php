<?php

namespace App\Controller\Back;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Repository\ChatRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use DateTime;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        return $this->render('back/conversation/index.html.twig', ['conversations' => $conversationRepository->findAll()]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_show')]
    public function show(Conversation $conversation): Response
    {
        return $this->render('back/conversation/show.html.twig', ['conversation' => $conversation]);
    }

    #[Route('/conversation/{id}/answer', name: 'app_chat_create')]
    public function create(Request $request, ChatRepository $chatRepository, Conversation $conversation, UserRepository $userRepository)
    {
        $chat = new Chat();
        $data = json_decode($request->getContent(), true);
        $message = $data['message'];
        $user_id = $this->getUser() ? $this->getUser()->getId() : null;
        if ($user_id == null) {
            return new Response('Error not found user', 403);
        }
        $user = $userRepository->findOneBy([
            'email' => $this->getUser()->getEmail(),
        ]);
        $chat
            ->setConversation($conversation)
            ->setText($message)
            ->setClient($user)
            ->setTimestamp(new DateTime());
        $chatRepository->save($chat, true);
        $response = new Response('Ok');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
