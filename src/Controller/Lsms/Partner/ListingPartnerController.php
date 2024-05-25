<?php

namespace App\Controller\Lsms\Partner;

use App\Repository\PartnerRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ListingPartnerController extends AbstractController
{
    #[Route('/partenaire', name: 'app_lsms_partner')]
    public function index(Request $request, ConnectService $connectService, PartnerRepository $partnerRepository): Response
    { 
         /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $partners= $partnerRepository->findBy([],["name"=>"ASC"]);
        return $this->render('lsms/partner/listing_partner/index.html.twig', [
            'partners' => $partners,
        ]);
    }
}
