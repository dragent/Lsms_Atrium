<?php

namespace App\Controller\Staff\Log;

use App\Service\ConnectService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    #[Route('/admin/service', name: 'app_staff_log_service')]
    public function index(Request $request,ConnectService $connectService, UserRepository $userRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $users = $userRepository->findByRole("ROLE_LSMS",["column"=>"Username","order"=>"ASC"]);
        return $this->render('staff/log/service/index.html.twig', [
            'users'=>$users,
        ]);
    }
}
