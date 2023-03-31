<?php

namespace App\Controller\Front;

use App\Entity\Chat;
use App\Entity\Conversation;
use App\Repository\ChatRepository;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeZone;
use http\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/conversation'), IsGranted('ROLE_USER')]

class ConversationController extends AbstractController
{
    #[Route('/listen/{id<\d+>?}')]
    public function listen(int $id = null, ConversationRepository $conversationRepository): JsonResponse
    {
        $result = [];
        if ($id === null) {
            $timezone = new DateTimeZone('Europe/Paris');
            $today = (new DateTime('now', $timezone))->format('Y-m-d');
            $today = DateTime::createFromFormat('Y-m-d', $today);
            $conversation = $conversationRepository->findOneBy([
                'client' => $this->getUser(),
                'source'=>'chat'
            ], ['timestamp'=>'DESC']);
            if ($conversation === null || $conversation?->getTimestamp()->diff($today)->d >= 1 ){
                $conversation = new Conversation();
            }
        } else {
            $conversation = $conversationRepository->find($id);
        }
        $conversation->setSource("chat");
        foreach ($conversation->getChat()->toArray() as $chat) {
            $result[] = [
                'message' => $chat->getText(),
                'timestamp' => $chat->getTimestamp(),
                'user' => [
                    "lastname" => $chat->getClient()->getLastname(),
                    "firstname" => $chat->getClient()->getFirstname(),
                    "role" => $chat->getClient()->getRoles()[0],
                ]
            ];
        }
        return new JsonResponse($result);

    }

}
