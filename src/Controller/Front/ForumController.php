<?php

namespace App\Controller\Front;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Form\ChatType;
use App\Repository\ChatRepository;
use App\Repository\ConversationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum', name: 'app_forum_')]
class ForumController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        $conversations = $conversationRepository->findBy(['source' => 'forum']);
        return $this->render('front/forum/index.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ChatRepository $chatRepository, ConversationRepository $conversationRepository): Response
    {
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conversation = new Conversation();
            $conversation->setSource("forum");
            $conversation->setClient($this->getUser());
            $conversation->setTimestamp(new DateTime());
            $chat->setClient($this->getUser())
                ->setTimestamp(new DateTime())
                ->setConversation($conversation);
            $conversationRepository->save($conversation, true);
            $chatRepository->save($chat, true);
        }
        return $this->render('front/forum/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Request $request, Conversation $conversation, ChatRepository $chatRepository): Response
    {
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chat->setConversation($conversation);
            $chat->setClient($this->getUser());
            $chat->setTimestamp(new DateTime());
            $chatRepository->save($chat, true);
            $chat = new Chat();
            $form = $this->createForm(ChatType::class, $chat);
            $this->redirectToRoute('front_app_forum_show', ['id' => $conversation->getId()]);
        }
        return $this->render('front/forum/show.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView()
        ]);
    }


}
