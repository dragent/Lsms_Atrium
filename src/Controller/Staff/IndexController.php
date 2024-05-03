<?php

namespace App\Controller\Staff;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    #[Route('/admin', name: 'app_staff')]
    public function index(Request $request, ConnectService $connectService): Response
    {   
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole!=true ) 
            return $this->redirectToRoute($checkRole);
        return $this->render('staff/index/index.html.twig', [
        ]);
    }
}
