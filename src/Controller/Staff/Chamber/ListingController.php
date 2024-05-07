<?php

namespace App\Controller\Staff\Chamber;

use App\Service\ConnectService;
use App\Repository\ChamberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListingController extends AbstractController
{
    #[Route('/admin/chambre', name: 'app_staff_chamber')]
    public function index(Request $request, ConnectService $connectService, ChamberRepository $chamberRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $chambers = $chamberRepository->findBy([],["name"=>"ASC"]);
        return $this->render('staff/chamber/listing/index.html.twig', [
            'chambers'=>$chambers,
        ]);
    }
}
