<?php

namespace App\Controller\Staff\Log;

use App\Service\ConnectService;
use App\Repository\ComptabilityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComptabilityController extends AbstractController
{
    #[Route('/admin/comptabilité', name: 'app_staff_log_comptability')]
    public function index(Request $request, ConnectService $connectService, ComptabilityRepository $comptabilityRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $comptability = $comptabilityRepository->find(1);
        return $this->render('staff/log/comptability/index.html.twig', [
            'comptability' => $comptability,
        ]);
    }
}
