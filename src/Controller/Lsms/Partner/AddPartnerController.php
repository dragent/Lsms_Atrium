<?php

namespace App\Controller\Lsms\Partner;

use App\Service\ConnectService;
use App\Service\PartnerService;
use App\Repository\PartnerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddPartnerController extends AbstractController
{
    #[Route('/lsms/partenaire/ajout', name: 'app_lsms_partner_add')]
    public function index(Request $request, ConnectService $connectService,PartnerRepository $partnerRepository, PartnerService $partnerService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        if($request->get('action')==="accept")
        {
            $slug = strtolower(str_replace(" ","-",$request->get('name')));
            if($partnerRepository->findOneBy(["slug"=>$slug]) !== null)
            {
                $session->getFlashBag()->set('warning', "Le partenaire existe déja");
                return $this->redirectToRoute("app_lsms_partner",[],302);
            }
            $partnerService->add($request->get('name'));
        }
        return $this->redirectToRoute("app_lsms_partner",[],302);
    }
}
