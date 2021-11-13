<?php

namespace App\Controller\Speciality;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialityController extends AbstractController
{
    protected SpecialityRepository $specialityRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(SpecialityRepository $specialityRepository, EntityManagerInterface $entityManager)
    {
        $this->specialityRepository = $specialityRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/admin/cree-une-specialite", name="create_speciality")
     */
    public function createSpeciality(Request $request): Response
    {
        $speciality = new Speciality();
        $form = $this->createForm(SpecialityType::class, $speciality)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search_exist = $this->specialityRepository->findByName($speciality->getName());
            if (!$search_exist) {
                $this->entityManager->persist($speciality);
                $this->entityManager->flush();
                $this->addFlash('success', 'La spécialité a bien été ajouter');
                return $this->redirectToRoute('homePage');
            }
            $this->addFlash('warning', 'Cette spécialité existe déjà');
            $form = $this->createForm(SpecialityType::class, $speciality)->handleRequest($request);
            return $this->render('agent/speciality/create.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('agent/speciality/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
