<?php

namespace App\Controller\Staff\Log\CareSheet;

use App\Repository\CareSheetRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CareSheetController extends AbstractController
{
    #[Route('/admin/fiche-de-soin', name: 'app_staff_log_caresheet')]
    public function index(CareSheetRepository $careSheetRepository, Request $request, ConnectService $connectService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $caresheets =  $careSheetRepository->findBy([],["medic"=>"ASC","dateCare"=>"ASC"]);
        return $this->render('staff/log/care_sheet/index.html.twig', [
            'caresheets'=>$caresheets
        ]);
    }
}
