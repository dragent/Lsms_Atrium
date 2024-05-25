<?php

namespace App\Controller\Lsms;

use App\Repository\CareRepository;
use App\Repository\CategoryHealthRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    #[Route('/lsms', name: 'app_lsms_index')]
    public function index(Request $request, ConnectService $connectService, CategoryHealthRepository $categoryHealthRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $careCategories = $categoryHealthRepository->findAll();
        
        return $this->render('lsms/index/index.html.twig', [
            'categories'=>$careCategories,
            'titleBis'=>'Tarif'
        ]);
    }
}
