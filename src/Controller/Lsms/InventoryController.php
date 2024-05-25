<?php

namespace App\Controller\Lsms;

use App\Repository\ObjectsRepository;
use App\Service\ConnectService;
use App\Service\InventoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class InventoryController extends AbstractController
{
    #[Route('/inventaire', name: 'app_lsms_inventory')]
    public function index(Request $request, ConnectService $connectService,ObjectsRepository $objectsRepository, InventoryService $inventoryService, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_AMBULANCIER'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $inventory = $objectsRepository->findBy([],["name"=>"ASC"]);
        if($request->request->get("action")==="fabricate")
            $inventoryService->fabricate($objectsRepository, $em, $request->request);
        else if($request->request->get("action")==="order")
        {
            $inventoryService->order($objectsRepository, $em, $request->request, $this->getUser());
        }
            
        return $this->render('lsms/inventory/index.html.twig', [
            'inventory'=>$inventory
        ]);
    }
}
