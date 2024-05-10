<?php

namespace App\Controller\Staff\CategoryHealth;

use App\Repository\CategoryHealthRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ListingController extends AbstractController
{
    #[Route('/admin/categorie-soins', name: 'app_staff_category_healh')]
    public function index(Request $request, ConnectService $connectService, CategoryHealthRepository $categoryHealthRepository): Response
    {   
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        return $this->render('staff/category_health/listing/index.html.twig', [
            'titleBis'=>'Recensement des catégories de soin',
        ]);
    }
}
