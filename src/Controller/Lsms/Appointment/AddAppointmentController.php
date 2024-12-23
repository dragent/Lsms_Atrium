<?php

namespace App\Controller\Lsms\Appointment;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Appointment;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddAppointmentController extends AbstractController
{
    #[Route('/lsms/planning/ajout', name: 'app_lsms_appointment_add')]
    public function index(Request $request, ConnectService $connectService, AppointmentRepository $repository,EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var User */
        $user = $this->getUser();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->json("Vous n'êtes pas connectés");
        if($user->getInService() == False)
            return $this->json("Vous n'êtes pas êtes pas en service");
        if(!$request->request->has("date") || !$request->request->has("id"))
            return $this->json("Vous n'avez pas les éléments pour travailler'");
        /**
         * @var Appointment
         */
        $appointment = $repository->find($request->request->get("id"));
        $appointment->setMedic($user);
        $date = explode("(",$request->request->get("date"))[0];
        $appointmentDate = new DateTimeImmutable($date);
        $appointment->setAppointmentAt($appointmentDate);
        $em->persist($appointment);
        $em->flush();
        return $this->json("Le rendez vous a bien été pris");
    }
}
