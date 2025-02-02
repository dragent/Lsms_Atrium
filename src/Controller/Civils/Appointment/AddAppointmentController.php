<?php

namespace App\Controller\Civils\Appointment;

use App\Entity\User;
use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddAppointmentController extends AbstractController
{
    #[Route('/prise-rendez-vous', name: 'app_civils_appointment_add_appointment')]
    public function index(Request $request, ConnectService $connectService, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkCivils($this->getUser(),$session,!$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            /** @var User */
            $user = $this->getUser();
            $user->addCivilAppointment($appointment);
            $em->persist($user);
            $em->persist($appointment);
            $em->flush();
            $session->getFlashBag()->set('success', "Le rendez-vous a bien été pris en compte");
        }
        return $this->render('civils/appointment/add_appointment/index.html.twig', [
            'form'=>$form
        ]);
    }
}
