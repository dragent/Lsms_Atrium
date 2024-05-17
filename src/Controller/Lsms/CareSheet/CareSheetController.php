<?php

namespace App\Controller\Lsms\CareSheet;

use App\Repository\CareRepository;
use App\Repository\CategoryHealthRepository;
use App\Repository\PartnerRepository;
use App\Service\CareSheetService;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CareSheetController extends AbstractController
{
    #[Route('/lsms/fiche-soins', name: 'app_lsms_care_sheet')]
    public function index(Request $request, ConnectService $connectService,CareRepository $careRepository, CategoryHealthRepository $categoryHealthRepository, PartnerRepository $partnerRepository, CareSheetService $careSheetService, EntityManagerInterface $em): Response
    {    
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $careCategories = $categoryHealthRepository->findAll();
        if($request->request->count()!=0)
            $careSheetService->saveCareSheet($em,$request->request,$careRepository,$this->getUser(), $partnerRepository);
        $partners = $partnerRepository->findBy([],["name"=>"ASC"]);
        return $this->render('lsms/care_sheet/index.html.twig', [
            'categories'=>$careCategories,
            'partners'=>$partners,
        ]);
    }
}
