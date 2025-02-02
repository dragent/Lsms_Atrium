<?php

namespace App\Controller\Lsms\Partner;

use App\Service\ConnectService;
use App\Repository\PartnerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyPartnerController extends AbstractController
{
    #[Route('/lsms/partenaire/{slug}', name: 'app_lsms_partner_modify')]
    public function index(Request $request, ConnectService $connectService, PartnerRepository $partnerRepository, string $slug): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $partner = $partnerRepository->findOneBy(["slug"=>$slug]);
        return $this->render('lsms/partner/modify_partner/index.html.twig', [
            'partner' => $partner,
        ]);
    }
}
