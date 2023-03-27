<?php

namespace App\Controller\Back;

use App\Entity\Hackathon;
use App\Form\HackathonType;
use App\Repository\HackathonRepository;
use App\Repository\YearRepository;
use App\Security\Voter\HackathonVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hackathon', name: 'hackathon_')]
class HackathonController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(HackathonRepository $hackathonRepository, YearRepository $yearRepository, Request $request): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            if ($request->getMethod() === 'POST') {
                $search = $request->request->get('search');
                $year = $request->request->get('year');
                $hackathons = $hackathonRepository->search($search, $year, 10);
            } else {
                $hackathons = $hackathonRepository->findBy([], ['position' => 'ASC']);
            }
        } else {
            $hackathons = $this->getUser()->getHackathons();
        }

        $years = $yearRepository->findAll();

        return $this->render('back/hackathon/index.html.twig', [
            'hackathons' => $hackathons,
            'years' => $years,
        ]);
    }

    #[Route('/{id}/sortable/{position}', name: 'sortable', requirements: ['position' => 'UP|DOWN'], methods: ['GET'])]
    public function sortable(Hackathon $hackathon, $position, EntityManagerInterface $manager): Response
    {
        $position === 'UP' ? $hackathon->setPosition($hackathon->getPosition() -1) : $hackathon->setPosition($hackathon->getPosition() +1);
        $manager->flush();

        return $this->redirectToRoute('back_hackathon_index');
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_COACH')]
    public function create(Request $request, HackathonRepository $hackathonRepository): Response
    {
        $hackathon = new Hackathon();
        $form = $this->createForm(HackathonType::class, $hackathon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hackathonRepository->save($hackathon, true);

            return $this->redirectToRoute('back_hackathon_show', [
                'id' => $hackathon->getId()
            ]);
        }

        return $this->render('back/hackathon/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    /** #[IsGranted(HackathonVoter::EDIT, 'hackathon')] */
    #[Security('is_granted("ROLE_ADMIN") or hackathon.getCreatedBy() === user')]
    public function edit(Hackathon $hackathon, Request $request, ManagerRegistry $managerRegistry): Response
    {
        // Same to attributes 'IsGranted'
        //$this->denyAccessUnlessGranted(HackathonVoter::EDIT, $hackathon);

        $form = $this->createForm(HackathonType::class, $hackathon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('back_hackathon_show', [
                'id' => $hackathon->getId()
            ]);
        }

        return $this->render('back/hackathon/edit.html.twig', [
            'form' => $form->createView(),
            'hackathon' => $hackathon
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted(HackathonVoter::SHOW, 'hackathon')]
    public function show(Hackathon $hackathon): Response
    {
        return $this->render('back/hackathon/show.html.twig', [
            'hackathon' => $hackathon,
            'test' => 'Coucou <br> test <script>alert()</script>'
        ]);
    }

    #[Route('/{id}/remove/{token}', name: 'remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted(HackathonVoter::DELETE, 'hackathon')]
    public function remove(Hackathon $hackathon, string $token, HackathonRepository $hackathonRepository): Response
    {
        if (!$this->isCsrfTokenValid('remove' . $hackathon->getId(), $token)) {
            throw $this->createAccessDeniedException();
        }

        $hackathonRepository->remove($hackathon, true);

        return $this->redirectToRoute('back_hackathon_index');
    }
}
