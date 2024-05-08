<?php

namespace App\Controller\Staff\Object;

use App\Service\ConnectService;
use App\Repository\ObjectsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/admin/inventaire', name: 'app_staff_object')]
    public function index(Request $request,ConnectService $connectService, ObjectsRepository $objectRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $objects = $objectRepository->findBy([],["name"=>"ASC"]);

        return $this->render('staff/object/index/index.html.twig', [
            'inventory'=>$objects,
        ]);
    }
}
