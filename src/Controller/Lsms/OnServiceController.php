<?php

namespace App\Controller\Lsms;

use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Service;
use App\Service\ConnectService;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OnServiceController extends AbstractController
{
    #[Route('/lsms/service', name: 'app_lsms_on_service')]
    public function index(Request $request, ConnectService $connectService, EntityManagerInterface $em, ServiceRepository $serviceRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->json("Vous n'êtes pas connectés");
        if($request->get("onService")=== null)
            return $this->redirectToRoute("app_lsms_index");
            /** @var User */
        $user = $this->getUser();
        $user->setInService($request->get("onService")==="true");
        if($user->getInService())
        {
            $service = new Service();
            $service->setStartAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
            $user->addService($service);
            $message =$user->getUsername() . " a commencé son service";
        }
        else
        {
            $service = $serviceRepository->findLastEntry($user->getId());
            $service->setFinishAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
            $message = $user->getUsername() . " n'est plus en service";
        }   
        $em->persist($service);
        $em->persist($user);
        $em->flush();
        return $this->json($message);
    }
}
