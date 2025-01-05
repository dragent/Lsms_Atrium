<?php

namespace App\Controller\Civils\Appointments;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlanningListController extends AbstractController
{
    #[Route('/civils/appointments/planning/list', name: 'app_civils_appointments_planning_list')]
    public function index(): Response
    {
        return $this->render('civils/appointments/planning_list/index.html.twig', [
            'controller_name' => 'PlanningListController',
        ]);
    }
}
