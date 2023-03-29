<?php

namespace App\Controller\Back;

use App\Entity\Content;
use App\Entity\Media;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\ContentTypeRepository;
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
class ContentsController extends AbstractController
{
    #[Route('/', name: 'app_content_index', methods: ['GET'])]
    public function index(ContentRepository $contentRepository, MediaRepository $mediaRepository): Response
    {
        $imgs = $mediaRepository->findAll();
        return $this->render('back/content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
            'imgs' => $imgs,
        ]);
    }

    #[Route('/new', name: 'app_content_new', methods: ['GET', 'POST'])]
    public function new(Request $request,ContentRepository $contentRepository,MediaRepository $mediaRepository, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        $content->setCreatedAt(new \DateTimeImmutable());
        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere les images
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $fichier = $pictureService->add($image,$slugger);
                $img = new Media();
                $img->setMediaUrl($fichier);
                $img->setContent($content);
                $img->getTypeMedia('Image');
                $mediaRepository->save($img);
            }
            // on recupere la video
            $videos = $form->get('videos')->getData();
            foreach($videos as $video){
                $fichier_2 = $pictureService->add($video,$slugger);
                $vd = new Media();
                $vd->setMediaUrl($fichier_2);
                $vd->setContent($content);
                $vd->getTypeMedia('Video');
                $mediaRepository->save($vd);
            }
            $contentRepository->save($content, true);
            return $this->redirectToRoute('back_app_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/content/new.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_content_show', methods: ['GET'])]
    public function show(Content $content): Response
    {
        return $this->render('back/content/show.html.twig', [
            'content' => $content,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Content $content, ContentRepository $contentRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $content->setImage($newFilename);
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
    public function delete(Request $request, Content $content, ContentRepository $contentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $contentRepository->remove($content, true);
        }

        return $this->redirectToRoute('app_content_index', [], Response::HTTP_SEE_OTHER);
    }
}