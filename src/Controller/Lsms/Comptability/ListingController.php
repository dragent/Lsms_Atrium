<?php

namespace App\Controller\Lsms\Comptability;

use App\Repository\ComptabilityRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ListingController extends AbstractController
{
    #[Route('/comptabilité', name: 'app_lsms_comptability')]
    public function index(Request $request, ConnectService $connectService, ComptabilityRepository $comptabilityRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $comptability = $comptabilityRepository->find(1);
        return $this->render('lsms/comptability/listing/index.html.twig', [
            'comptability' => $comptability,
        ]);
    }
}
