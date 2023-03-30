<?php

namespace App\Controller\Back;

use App\Entity\Media;
use App\Entity\Template;
use App\Form\TemplateType;
use App\Repository\TemplateRepository;
use App\Service\PictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/template')]
class TemplateController extends AbstractController
{
    #[Route('/', name: 'app_template_index', methods: ['GET'])]
    public function index(TemplateRepository $templateRepository): Response
    {
        return $this->render('back/template/index.html.twig', [
            'templates' => $templateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TemplateRepository $templateRepository,SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere l'image
            $image = $form->get('image')->getData();
            $fichier = $pictureService->add($image,$slugger,'images_directory');
            $template->setImage($fichier);
            $templateRepository->save($template, true);
            return $this->redirectToRoute('back_app_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/template/new.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_template_show', methods: ['GET'])]
    public function show(Template $template): Response
    {
        return $this->render('back/template/show.html.twig', [
            'template' => $template,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Template $template, TemplateRepository $templateRepository, SluggerInterface $slugger,PictureService $pictureService): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on recupere l'image
            $image = $form->get('image')->getData();
            $fichier = $pictureService->add($image,$slugger,'images_directory');
            $template->setImage($fichier);
            $templateRepository->save($template, true);

            return $this->redirectToRoute('back_app_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/template/edit.html.twig', [
            'template' => $template,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_template_delete', methods: ['POST'])]
    public function delete(Request $request, Template $template, TemplateRepository $templateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$template->getId(), $request->request->get('_token'))) {
            $templateRepository->remove($template, true);
        }

        return $this->redirectToRoute('back_app_template_index', [], Response::HTTP_SEE_OTHER);
    }
}
