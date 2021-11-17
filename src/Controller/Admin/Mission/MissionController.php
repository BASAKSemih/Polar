<?php

namespace App\Controller\Admin\Mission;

use App\Entity\HidingPlace;
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
    protected EntityManagerInterface $entityManager;
    protected MissionRepository $missionRepository;

    public function __construct(EntityManagerInterface $entityManager, MissionRepository $missionRepository)
    {
        $this->missionRepository = $missionRepository;
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
            $countryMission = $mission->getCountry()->getName();
            $hidePlaces = $mission->getHidingPlace();
            foreach ($hidePlaces as $hidePlace) {
                if ($hidePlace->getCountry()->getName() !== $countryMission) {
                    $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                    $this->addFlash(
                        'danger',
                        "la planque est obligatoirement dans le même pays que la mission."
                    );
                    return $this->render('admin/mission/create.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }
            $contactsMission = $mission->getContact();
            foreach ($contactsMission as $contact) {
                foreach ($contact->getNationality() as $contactNationality) {
                    if ($countryMission !== $contactNationality->getCountry()->getName()) {
                        $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                        $this->addFlash(
                            'danger',
                            "les contacts sont obligatoirement de la nationalité du pays de la mission."
                        );
                        return $this->render('admin/mission/create.html.twig', [
                            'form' => $form->createView()
                        ]);
                    }
                }
            }
            $targetsMission = $mission->getTarget();
            $agentsMission = $mission->getAgent();
            foreach ($targetsMission as $target) {
                foreach ($target->getNationality() as $targetNationality) {
                    foreach ($agentsMission as $agent) {
                        foreach ($agent->getNationality() as $agentNationality) {
                            if ($targetNationality->getName() === $agentNationality->getName()) {
                                $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                                $this->addFlash(
                                    'danger',
                                    "La ou les cibles ne peuvent pas avoir la même nationalité que le ou les agents"
                                );
                                return $this->render('admin/mission/create.html.twig', [
                                    'form' => $form->createView()
                                ]);
                            }
                        }
                    }
                }
            }
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
            $countryMission = $mission->getCountry()->getName();
            $hidePlaces = $mission->getHidingPlace();
            foreach ($hidePlaces as $hidePlace) {
                if ($hidePlace->getCountry()->getName() !== $countryMission) {
                    $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                    $this->addFlash(
                        'danger',
                        "la planque est obligatoirement dans le même pays que la mission."
                    );
                    return $this->render('admin/mission/create.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }
            $contactsMission = $mission->getContact();
            foreach ($contactsMission as $contact) {
                foreach ($contact->getNationality() as $contactNationality) {
                    if ($countryMission !== $contactNationality->getCountry()->getName()) {
                        $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                        $this->addFlash(
                            'danger',
                            "les contacts sont obligatoirement de la nationalité du pays de la mission."
                        );
                        return $this->render('admin/mission/create.html.twig', [
                            'form' => $form->createView()
                        ]);
                    }
                }
            }
            $targetsMission = $mission->getTarget();
            $agentsMission = $mission->getAgent();
            foreach ($targetsMission as $target) {
                foreach ($target->getNationality() as $targetNationality) {
                    foreach ($agentsMission as $agent) {
                        foreach ($agent->getNationality() as $agentNationality) {
                            if ($targetNationality->getName() === $agentNationality->getName()) {
                                $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);
                                $this->addFlash(
                                    'danger',
                                    "La ou les cibles ne peuvent pas avoir la même nationalité que le ou les agents"
                                );
                                return $this->render('admin/mission/create.html.twig', [
                                    'form' => $form->createView()
                                ]);
                            }
                        }
                    }
                }
            }
            $this->entityManager->flush();
            $this->addFlash('success', "La mission à bien été modifier");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/mission/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
