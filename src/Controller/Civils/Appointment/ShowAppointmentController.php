<?php

namespace App\Controller\Civils\Appointment;

use App\Entity\User;
use App\Service\ConnectService;
use App\Service\FullCalendarService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowAppointmentController extends AbstractController
{
    #[Route('/planning/liste', name: 'app_civils_planning_list')]
    public function index(Request $request, ConnectService $connectService,FullCalendarService $fullCalendarService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var User */
        $user = $this->getUser();
        if($user===Null)
            return $this->json("Vous n'êtes pas connecté");
        $checkRole =  $connectService->checkCivils($this->getUser(),$session,!$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true )
            return $this->json("Vous n'êtes pas un médecin");
        return $this->json($fullCalendarService->adaptForCivil(array('civil'=>$user)));
    }
}
