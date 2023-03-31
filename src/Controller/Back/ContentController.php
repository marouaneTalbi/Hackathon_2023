<?php

namespace App\Controller\Back;

use App\Entity\Content;
use App\Entity\Media;
use App\Entity\Tag;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\MediaRepository;
use App\Repository\TagRepository;
use App\Service\PictureService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[Route('/content')]
class ContentController extends AbstractController
{
    #[Route('/', name: 'app_content_index', methods: ['GET'])]
    public function index(ContentRepository $contentRepository, MediaRepository $mediaRepository, TagRepository $tagRepository): Response
    {
        return $this->render('back/content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_content_new', methods: ['GET', 'POST'])]
    public function new(Request $request,ContentRepository $contentRepository,MediaRepository $mediaRepository, SluggerInterface $slugger, PictureService $pictureService, TagRepository $tagRepository): Response
    {


        $content = new Content();
        if(isset($_POST["htmlContent"]) && !empty($_POST["htmlContent"])) {
            $content->setCreatedAt(new \DateTimeImmutable());
            $content->setContent($_POST["htmlContent"]);
            $content->setType($_POST['type']);
            
            if(isset($_POST["titleContent"]) && !empty($_POST["titleContent"])){
                $content->setTitle($_POST["titleContent"]);
            }else{
                $content->setTitle("Article test");
            }
            $contentRepository->save($content, true);
        }
        // $htmlContent = $data['htmlContent'];

        /*if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = $pictureService->add($image,$slugger,'images_directory');
                $img = new Media();
                $img->setMediaUrl($fichier);
                $content->addMedia($img);
                $img->setTypeMedia('Image');
                $mediaRepository->save($img);
            }

            $videos = $form->get('videos')->getData();
            foreach($videos as $video){
                $fichier_2 = $pictureService->add($video,$slugger,'videos_directory');
                $vd = new Media();
                $vd->setMediaUrl($fichier_2);
                $content->addMedia($vd);
                $vd->setTypeMedia('Video');
                $mediaRepository->save($vd);
            }
            $contentRepository->save($content, true);
            return $this->redirectToRoute('back_app_content_index', [], Response::HTTP_SEE_OTHER);
        }*/

        return $this->renderForm('back/content/new.html.twig', [
            'content' => $content
            //'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_content_show', methods: ['GET'])]
    public function show(Content $content, MediaRepository $mediaRepository): Response
    {
        return $this->render('back/content/show.html.twig', [
            'content' => $content,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PictureService $pictureService, Content $content,MediaRepository $mediaRepository, ContentRepository $contentRepository, SluggerInterface $slugger, TagRepository $tagRepository): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = $pictureService->add($image,$slugger,'images_directory');
                $img = new Media();
                $img->setMediaUrl($fichier);
                $content->addMedia($img);
                $img->setTypeMedia('Image');
                $mediaRepository->save($img);
            }

            $videos = $form->get('videos')->getData();
            foreach($videos as $video){
                $fichier_2 = $pictureService->add($video,$slugger,'videos_directory');
                $vd = new Media();
                $vd->setMediaUrl($fichier_2);
                $content->addMedia($vd);
                $vd->setTypeMedia('Video');
                $mediaRepository->save($vd);
            }

            $contentRepository->save($content, true);
            return $this->redirectToRoute('back_app_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/content/edit.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_content_delete', methods: ['POST'])]
    public function delete(Request $request, Content $content,MediaRepository $mediaRepository, ContentRepository $contentRepository): Response
    {
        $medias = $mediaRepository->findBy(["content"=>$content->getId()]);
        foreach($medias as $media) {
            $mediaRepository->remove($media);
        }
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $contentRepository->remove($content, true);
        }

        return $this->redirectToRoute('back_app_content_index', [], Response::HTTP_SEE_OTHER);
    }
}
