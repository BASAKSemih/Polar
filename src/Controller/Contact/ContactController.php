<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected ContactRepository $contactRepository;

    public function __construct(EntityManagerInterface $entityManager, ContactRepository $contactRepository)
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
    }


    /**
     * @Route("/admin/cree-un-contact", name="create_contact")
     */
    public function createContact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->addFlash('success', "Le contact à bien été crée");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('contact/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modifier-un-contact/{idContact}", name="edit_contact")
     */
    public function editContact(Request $request, $idContact): Response
    {
        $contact = $this->contactRepository->findOneById($idContact);
        if (!$contact){
            $this->addFlash('warning', "Ce contact n'existe pas");
            return $this->redirectToRoute('homePage');
        }
        $form = $this->createForm(ContactType::class, $contact)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            $this->addFlash('success', "Le contact à bien été modifier");
            return $this->redirectToRoute('homePage');
        }
        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }



}