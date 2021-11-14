<?php

namespace App\Controller\Admin\Mission;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\{AgentRepository, ContactRepository,
    HidingPlaceRepository, MissionRepository,
    NationalityRepository, SpecialityRepository, TargetRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    protected AgentRepository $agentRepository;
    protected ContactRepository $contactRepository;
    protected HidingPlaceRepository $hidingPlaceRepository;
    protected MissionRepository $missionRepository;
    protected NationalityRepository $nationalityRepository;
    protected SpecialityRepository $specialityRepository;
    protected TargetRepository $targetRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        AgentRepository $agentRepository,
        ContactRepository $contactRepository,
        HidingPlaceRepository $hidingPlaceRepository,
        MissionRepository $missionRepository,
        NationalityRepository $nationalityRepository,
        SpecialityRepository $specialityRepository,
        TargetRepository $targetRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->agentRepository = $agentRepository;
        $this->contactRepository = $contactRepository;
        $this->hidingPlaceRepository = $hidingPlaceRepository;
        $this->missionRepository = $missionRepository;
        $this->nationalityRepository = $nationalityRepository;
        $this->specialityRepository = $specialityRepository;
        $this->targetRepository = $targetRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/admin/cree-une-mission", name="create_mission")
     */
    public function createMission(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($mission);
            $this->entityManager->flush();
            $this->addFlash('success', "La mission à bien été crée");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/mission/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-une-mission/{idMission}", name="edit_mission")
     */
    public function editMission($idMission, Request $request): Response
    {
        $mission = $this->missionRepository->findOneById($idMission);
        if (!$mission) {
            $this->addFlash('warning', "Cette mission n'existe pas");
            return $this->redirectToRoute('homePage');
        }
        $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', "La mission à bien été modifier");
            return $this->redirectToRoute('homePage');
        }

        return $this->render('admin/mission/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
