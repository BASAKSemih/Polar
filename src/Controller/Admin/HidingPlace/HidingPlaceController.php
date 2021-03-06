<?php

namespace App\Controller\Admin\HidingPlace;

use App\Entity\HidingPlace;
use App\Form\HidingPlaceType;
use App\Repository\HidingPlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class HidingPlaceController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected HidingPlaceRepository $hidingPlaceRepository;

    public function __construct(EntityManagerInterface $entityManager, HidingPlaceRepository $hidingPlaceRepository)
    {
        $this->entityManager = $entityManager;
        $this->hidingPlaceRepository = $hidingPlaceRepository;
    }


    /**
     * @Route("/admin/ajouter-une-planque", name="create_hidingPlace")
     */
    public function createHidingPlace(Request $request): Response
    {
        $hidingPlace = new HidingPlace();
        $form = $this->createForm(HidingPlaceType::class, $hidingPlace)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($hidingPlace);
            $this->entityManager->flush();
            $this->addFlash('success', "La planque à bien été ajouter");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/hidingPlace/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-une-planque/{idHidingPlace}", name="edit_hidingPlace")
     */
    public function editHidingPlace(Request $request, $idHidingPlace): Response
    {
        $hidingPlace = $this->hidingPlaceRepository->findOneById($idHidingPlace);
        if (!$hidingPlace) {
            $this->addFlash('warning', "La planque demander n'existe pas");
        }
        $form = $this->createForm(HidingPlaceType::class, $hidingPlace)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', "La planque à bien été modifier");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('admin/hidingPlace/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
