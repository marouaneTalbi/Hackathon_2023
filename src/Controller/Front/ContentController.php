<?php

namespace App\Controller\Front;

use App\Entity\Content;
use App\Entity\Media;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\MediaRepository;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/content')]
class ContentController extends AbstractController
{
    #[Route('/', name: 'app_content_index', methods: ['GET'])]
    public function index(ContentRepository $contentRepository, MediaRepository $mediaRepository): Response
    {
        $imgs = $mediaRepository->findAll();
        return $this->render('front/content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
            'imgs' => $imgs,
        ]);
    }

    #[Route('/{id}', name: 'app_content_show', methods: ['GET'])]
    public function show(Content $content, MediaRepository $mediaRepository): Response
    {
        $imgs = $mediaRepository->findBy(["content"=>$content->getId()]);
        return $this->render('front/content/show.html.twig', [
            'content' => $content,
            'imgs' => $imgs,
        ]);
    }


}
