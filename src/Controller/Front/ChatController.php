<?php

namespace App\Controller\Front;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Repository\ChatRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        $conversation = $conversationRepository->findBy(['client' => $this->getUser()], ['timestamp' => 'DESC']);
        $latest_conversation = count($conversation) > 0 ? $conversation[0] : null;
        $conversationDate = $latest_conversation?->getTimestamp();
        $currentDate = new DateTime('now');
        $isToday = ($conversationDate?->format('Y-m-d') === $currentDate->format('Y-m-d'));
        if ($isToday) {
            $conversation = $latest_conversation;
        } else {
            $conversation = new Conversation();
        }
        return $this->render('front/chat/index.html.twig', [
            'conversation' => $conversation,
        ]);
    }

    #[Route('/chat/create', name: 'app_chat_create')]
    public function create(Request $request, ChatRepository $chatRepository, ConversationRepository $conversationRepository, UserRepository $userRepository)
    {
        $chat = new Chat();
        $user_id = $this->getUser() ? $this->getUser()->getId() : null;
        if ($user_id == null) {
            return new Response('Error not found user', 403);
        }
        $data = json_decode($request->getContent(), true);
        $message = $data['message'];
        $user = $userRepository->findOneBy(['id' => $user_id]);
        $conversations = $conversationRepository->findBy(['client' => $user], ['timestamp' => 'DESC']);
        $latest_conversation = count($conversations) > 0 ? $conversations[0] : null;
        $conversationDate = $latest_conversation?->getTimestamp();
        $currentDate = new DateTime('now');
        $isToday = ($conversationDate?->format('Y-m-d') === $currentDate->format('Y-m-d'));
        if ($isToday) {
            $conversation = $latest_conversation;
        } else {
            $conversation = new Conversation();
        }
        $conversation
            ->setTimestamp(new DateTime())
            ->setClient($user);
        $chat
            ->setConversation($conversation)
            ->setText($message)
            ->setClient($user)
            ->setTimestamp(new DateTime());
        $conversationRepository->save($conversation, true);
        $chatRepository->save($chat, true);
        $response = new Response('Ok');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
