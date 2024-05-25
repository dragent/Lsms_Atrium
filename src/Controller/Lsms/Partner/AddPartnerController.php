<?php

namespace App\Controller\Lsms\Partner;

use App\Repository\PartnerRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddPartnerController extends AbstractController
{
    #[Route('/partenaire/ajout', name: 'app_lsms_partner_add')]
    public function index(Request $request, ConnectService $connectService,PartnerRepository $partnerRepository, EntityManagerInterface $em, PartnerService $partnerService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        if($request->get('accept'))
        {

        }
        dd($request->get('Accept'));
        if()
        $partners= $partnerRepository->findBy([],["name"=>"ASC"]);
        return $this->redirectToRoute("app_lsms_partner",[],302);
    }
}
