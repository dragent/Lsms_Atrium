<?php

namespace App\Controller\Lsms\Personel;

use App\Repository\UserRepository;
use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ListingPersonelController extends AbstractController
{
    #[Route('/personnel', name: 'app_lsms_personel')]
    public function index(Request $request, ConnectService $connectService, UserRepository $userRepository): Response
    {       
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_DIRECTION'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $users = $userRepository->findByRole("ROLE_LSMS",["column"=>"Username","order"=>"ASC"]);
        return $this->render('lsms/personel/listing_personel/index.html.twig', [
            'users' => $users,
        ]);
    }
}
