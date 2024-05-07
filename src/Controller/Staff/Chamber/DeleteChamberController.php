<?php

namespace App\Controller\Staff\Chamber;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteChamberController extends AbstractController
{
    #[Route('/staff/chamber/delete/{slug}', name: 'app_staff_chamber_delete')]
    public function index(string $slug, Request $request, ConnectService $connectService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        
        return $this->redirectToRoute('app_staff_object',[],302);

    }
}
