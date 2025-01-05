<?php

namespace App\Service;

use DateInterval;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Util\Json;
use App\Entity\Appointment;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentRepository;

class FullCalendarService
{
    private EntityManagerInterface $em;
    private AppointmentRepository $appointmentRepository;

    private UserRepository $userRepository;

    public function __construct( EntityManagerInterface $em, AppointmentRepository $appointmentRepository,UserRepository $userRepository ){
        $this->em= $em;
        $this->appointmentRepository= $appointmentRepository;
        $this->userRepository = $userRepository;
    }
    private function title(string $reason):string{
        switch ($reason) {
            case 'medicalVisit':
                return "Visite médicale";
            case 'ppa':
                return "Permis de Port d'Arme";
            case 'testPhy':
                return "Test physique";
            case 'rdvPsy':
                return "Rendez vous psy";
            default:
                return "Autre";
        }
    }

    public function adaptForMedic(array $medic): array
    {
        $returnArray=[];
        
        $appointments = $this->appointmentRepository->findBy($medic);
        /** @var Appointment */
        foreach ($appointments as  $appointment) {
            $title = $appointment->getReason();
            $person = $appointment->getCivil()->getUsername() . " - " . $appointment->getNumber();
            $description = $appointment->getDetail();
            $date = $appointment->getAppointmentAt();
            $end = $appointment->getAppointmentAt()->add(new DateInterval('PT30M'));
            $format=["title" => $this->title($title),"person"=>$person,'description'=>$description,'start'=>$date->format("Y-m-d\TH:i:s"),"end"=>$end->format("Y-m-d\TH:i:s"),'id'=>$appointment->getId()];
            array_push($returnArray,$format);
        }

        return $returnArray;
    }

    public function adaptForCivil(array $medic): array
    {
        $returnArray=[];
        
        $appointments = $this->appointmentRepository->findBy($medic);
        /** @var Appointment */
        foreach ($appointments as  $appointment) {
            $title = $appointment->getReason();
            $medic = $appointment->getMedic()->getUsername() . " - " . $appointment->getNumber();
            $description = $appointment->getDetail();
            $date = $appointment->getAppointmentAt();
            $end = $appointment->getAppointmentAt()->add(new DateInterval('PT30M'));
            $format=["title" => $this->title($title),"doctor"=>$medic,'description'=>$description,'start'=>$date->format("Y-m-d\TH:i:s"),"end"=>$end->format("Y-m-d\TH:i:s"),'id'=>$appointment->getId()];
            array_push($returnArray,$format);
        }

        return $returnArray;
    }

    public function modification(int $idAppointment,string $motif, $argument)
    {
        /**
         * @var Appointment
         */
        $appointment = $this->appointmentRepository->find($idAppointment);
        switch($motif)
        {
            case "date": 
                $date = explode("(",$argument)[0];
                $appointment->setAppointmentAt(new DateTimeImmutable($date));
                $this->em->persist($appointment);
                $this->em->flush();
                break;
            case "doctor" : 
                $appointment->setMedic($this->userRepository->find($argument));
                $this->em->persist($appointment);
                $this->em->flush();
                break;
            case "detail":
                $appointment->setDetail($argument);
                $this->em->persist($appointment);
                $this->em->flush();
                break;
            default;
        }
    }

    public function add($request,User $user)
    {
                /**
         * @var Appointment
         */
        $appointment = $this->appointmentRepository->find($request->get("id"));
        $appointment->setMedic($user);
        $date = explode("(",$request->get("date"))[0];
        $appointmentDate = new DateTimeImmutable($date);
        $appointment->setAppointmentAt($appointmentDate);
        $this->em->persist($appointment);
        $this->em->flush();
    }

}