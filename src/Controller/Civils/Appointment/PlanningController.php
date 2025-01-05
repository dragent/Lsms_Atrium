<?php

namespace App\Controller\Civils\Appointment;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlanningController extends AbstractController
{
    #[Route('planning', name: 'app_civils_planning')]
    public function index(Request $request, ConnectService $connectService,): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkCivils($this->getUser(),$session,!$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        return $this->render('civils/appointment/planning/index.html.twig', [
            'controller_name' => 'PlanningController',
        ]);
    }
}
