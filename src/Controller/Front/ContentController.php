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


class ContentController extends AbstractController
{
    #[Route('/content', name: 'app_content', methods: ['GET'])]
    public function index(ContentRepository $contentRepository, MediaRepository $mediaRepository, TagRepository $tagRepository): Response
    {
        $imgs = $mediaRepository->findAll();
        $resultas2 =[];
        if (isset($_GET['filter'])){
            $types = htmlspecialchars($_GET['type']);
            if($types != null){
                $tabTypes = explode(",", $types);
                foreach ($tabTypes as $type){
                    $resultasType = $contentRepository->findBy(["type"=>$type]);
                    foreach ($resultasType as $res){
                        $resultas2 [] = $res;
                    }
                }
            }
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
            function compare_objects($a, $b) {
                return strcmp(spl_object_hash($a), spl_object_hash($b));
            }
            $merged = array_merge($resultas, $resultas2);
            $unique = array_unique($merged);
            $result = array_values($unique);
            return $this->render('front/content/index.html.twig', [
                'contents' => $result,
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

    #[Route('/advice', name: 'app_advice', methods: ['GET'])]
    public function advice(ContentRepository $contentRepository, MediaRepository $mediaRepository, TagRepository $tagRepository): Response
    {
        $contents = $contentRepository->findBy(['type' => 'Conseil']);
        return $this->render('front/content/index.html.twig', [
            'contents' => $contents,
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/discussion', name: 'app_discussion', methods: ['GET'])]
    public function discussion(ContentRepository $contentRepository, MediaRepository $mediaRepository, TagRepository $tagRepository): Response
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
