<?php

namespace App\Controller\Staff;

use App\Repository\UserRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    #[Route('/admin', name: 'app_staff')]
    public function index(Request $request, ConnectService $connectService, UserRepository $userRepository): Response
    {   
        
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        
        if($request->get("action")=="fetch_data")
            return $this->json($userRepository->findLSMSConnected(["column"=>"Username","order"=>"ASC"]));
        return $this->render('staff/index/index.html.twig', [
        ]);
    }
}
