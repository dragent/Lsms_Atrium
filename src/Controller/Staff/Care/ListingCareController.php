<?php

namespace App\Controller\Staff\Care;

use App\Repository\CareRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListingCareController extends AbstractController
{
    #[Route('/admin/soin', name: 'app_staff_care')]
    public function index(Request $request, ConnectService $connectService, CareRepository $careRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $cares=$careRepository->findAll();
        return $this->render('staff/care/listing_care/index.html.twig', [
            'titleBis'=>'Recensement des soins possibles',
            'cares'=>$cares
        ]);
    }
}
