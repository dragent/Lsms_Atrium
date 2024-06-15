<?php

namespace App\Controller\Civils\Appointment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlanningController extends AbstractController
{
    #[Route('/civils/appointment/planning', name: 'app_civils_appointment_planning')]
    public function index(): Response
    {
        return $this->render('civils/appointment/planning/index.html.twig', [
            'controller_name' => 'PlanningController',
        ]);
    }
}
