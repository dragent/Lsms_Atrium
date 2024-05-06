<?php

namespace App\Controller\Staff\Staff;

use App\Service\ConnectService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListController extends AbstractController
{
    #[Route('/admin/personnel', name: 'app_staff_staff')]
    public function index(Request $request,ConnectService $connectService, UserRepository $userRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $users = $userRepository->findByRole("ROLE_LSMS",["column"=>"Username","order"=>"ASC"]);
        return $this->render('staff/staff/list/index.html.twig', [
            'users'=>$users,
        ]);
    }
}
