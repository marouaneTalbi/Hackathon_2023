<?php

namespace App\Controller\Front;

use App\Entity\Content;
use App\Entity\Media;
use App\Entity\Tag;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(ContentRepository $contentRepository, MediaRepository $mediaRepository, TagRepository $tagRepository): Response
    {
        $imgs = $mediaRepository->findAll();
        if (isset($_GET['filter'])){
            $filter = htmlspecialchars($_GET['filter']);
            $tabs = explode(",", $filter);
            $resultas = [];
            foreach ($tabs as $tab) {
                $tag = $tagRepository->findOneBy(["name" => $tab]);
                if ($tag) {
                    $contents = $tag->getContents();
                    foreach ($contents as $content) {
                        $resultas[] = $content;
                    }
                }
            }
            return $this->render('front/content/index.html.twig', [
                'contents' => $resultas,
                'imgs' => $imgs,
                'tags' => $tagRepository->findAll()
            ]);
        }
        else{
            return $this->render('front/content/index.html.twig', [
                'contents' => $contentRepository->findAll(),
                'imgs' => $imgs,
                'tags' => $tagRepository->findAll()
            ]);
        }

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
