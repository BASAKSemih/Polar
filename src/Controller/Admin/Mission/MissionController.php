<?php

namespace App\Controller\Admin\Mission;

use App\Entity\Mission;
use App\Form\MissionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    /**
     * @Route("/admin/cree-une-mission", name="create_mission")
     */
    public function createMission(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission)->handleRequest($request);

        return $this->render('admin/mission/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
