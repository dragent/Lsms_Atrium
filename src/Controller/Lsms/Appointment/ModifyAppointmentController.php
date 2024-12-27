<?php

namespace App\Controller\Lsms\Appointment;

use App\Entity\User;
use App\Service\ConnectService;
use App\Service\FullCalendarService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyAppointmentController extends AbstractController
{
    #[Route('/lsms/planning/modif', name: 'app_lsms_appointment_modify')]
    public function index(Request $request,ConnectService $connectService,FullCalendarService $fullCalendarService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var User */
        $user = $this->getUser();
        if($user===Null)
            return $this->json("Vous n'êtes pas connecté");
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->json("Vous n'êtes pas un médecin");
        if($user->getInService() == False)
            return $this->json("Vous n'êtes pas êtes pas en service");
        if(!$request->request->has("motif") || !$request->request->has("id"))
            return $this->json("Vous n'avez pas les éléments pour travailler'");

        $fullCalendarService->modification(intval($request->request->get("id")),$request->request->get("motif"),$request->request->get("argument"));
        return $this->json($request->request);
        
    }
}
