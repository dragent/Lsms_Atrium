<?php

namespace App\Controller\Lsms\Personel;

use App\Service\ConnectService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiListDoctorController extends AbstractController
{
    #[Route('/personnel/liste', name: 'app_lsms_personel_api_list_doctor')]
    public function index(Request $request, ConnectService $connectService, UserService $userService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $userArray = $userService->getDoctors();
        return $this->json($userArray);
    }
}
