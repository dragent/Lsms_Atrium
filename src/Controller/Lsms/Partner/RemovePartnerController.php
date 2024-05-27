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

class RemovePartnerController extends AbstractController
{
    #[Route('//partenaire/suppression', name: 'app_lsms_partner_delete')]
    public function index(Request $request, ConnectService $connectService,PartnerRepository $partnerRepository, PartnerService $partnerService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        if($request->query->count()>0)
        {   
            $id = $request->query->get('id');
            if($partnerRepository->find($id) === null)
            {
                $session->getFlashBag()->set('warning', "Le partenaire n'existe pas");
                return $this->redirectToRoute("app_lsms_partner",[],302);
            }
            $partnerService->remove($id);
        }
        return $this->redirectToRoute("app_lsms_partner",[],302);
    }
}
