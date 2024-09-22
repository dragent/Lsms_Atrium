<?php

namespace App\Controller\Lsms\Appointment;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddAppointmentController extends AbstractController
{
    #[Route('/lsms/planning/ajout', name: 'app_lsms_appointment_add')]
    public function index(Request $request, ConnectService $connectService, ): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->json("Vous n'êtes pas connectés");
        if($request->get("onService")=== null)
            return $this->json("Vous n'êtes pas êtes pas autorisé à faire cela");

        $message="";
        return $this->json($message);
    }
}
