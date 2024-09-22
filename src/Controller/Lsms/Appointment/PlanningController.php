<?php

namespace App\Controller\Lsms\Appointment;

use App\Repository\AppointmentRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PlanningController extends AbstractController
{
    #[Route('/lsms/planning', name: 'app_lsms_planning')]
    public function index(Request $request, ConnectService $connectService, AppointmentRepository $appointmentRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $noMedicAppointments = $appointmentRepository->findBy(["medic"=>null],["id"=>"ASC"]);
        return $this->render('lsms/appointment/planning/index.html.twig', [
            'controller_name' => 'PlanningController',
            'noMedicAppointments'=>$noMedicAppointments,
        ]);
    }
}
