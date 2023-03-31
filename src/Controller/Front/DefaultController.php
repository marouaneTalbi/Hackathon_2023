<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;

#[IsGranted('ROLE_USER')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
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
        return $this->render('front/content/index.html.twig');
    }
}