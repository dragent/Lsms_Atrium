<?php

namespace App\Controller\Lsms\Comptability;

use App\Service\ConnectService;
use App\Service\ComptabilityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddController extends AbstractController
{
    #[Route('/comptabilité/ajout', name: 'app_lsms_comptability_add')]
    public function index(Request $request, ConnectService $connectService, ComptabilityService $comptabilityService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        if($request->get('action')==="entry")
            $comptabilityService->add(intval($request->get('price')),$request->get('reason'));
        elseif($request->get('action')==="out")
            $comptabilityService->remove(intval($request->get('price')),$request->get('reason'));
        return $this->redirectToRoute("app_lsms_comptability",[],302);
    }
}
