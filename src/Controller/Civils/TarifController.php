<?php

namespace App\Controller\Civils;

use App\Service\ConnectService;
use App\Repository\CategoryHealthRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TarifController extends AbstractController
{
    #[Route('/accueil', name: 'app_civils_tarif')]
    public function index(Request $request, ConnectService $connectService, CategoryHealthRepository $categoryHealthRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkCivils($this->getUser(),$session,!$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);

        $careCategories = $categoryHealthRepository->findAll();
        return $this->render('civils/tarif/index.html.twig', [
            'categories'=>$careCategories,
            'titleBis'=>'Tarif'
        ]);
    }
}
