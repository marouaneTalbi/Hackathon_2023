<?php

namespace App\Controller\Front;

use App\Entity\Content;
use App\Repository\ContentRepository;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContentController extends AbstractController
{
    #[Route('/content', name: 'app_content', methods: ['GET'])]
    public function index(TagRepository $tagRepository, ContentRepository $contentRepository): Response
    {
        return $this->render('front/content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/advice', name: 'app_advice', methods: ['GET'])]
    public function advice(ContentRepository $contentRepository, TagRepository $tagRepository): Response
    {
        $contents = $contentRepository->findBy(['type' => 'Conseil']);
        return $this->render('front/content/index.html.twig', [
            'contents' => $contents,
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/discussion', name: 'app_discussion', methods: ['GET'])]
    public function discussion(ContentRepository $contentRepository, TagRepository $tagRepository): Response
    {
        $contents = $contentRepository->findBy(['type' => 'Discussion']);
        return $this->render('front/content/index.html.twig', [
            'contents' => $contents,
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/content/{id}', name: 'app_content_show', methods: ['GET'])]
    public function show(Content $content, MediaRepository $mediaRepository): Response
    {
        $imgs = $mediaRepository->findBy(["content"=>$content->getId()]);
        return $this->render('front/content/show.html.twig', [
            'content' => $content,
            'imgs' => $imgs,
        ]);
    }


}
