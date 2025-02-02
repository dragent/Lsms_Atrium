<?php

namespace App\Controller\Lsms;

use App\Service\OrderService;
use App\Service\ConnectService;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/lsms/commande', name: 'app_lsms_order')]
    public function index(Request $request,  ConnectService $connectService, OrderRepository $orderRepository, OrderService $orderService): Response
    {  
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole !== true ) 
        return $this->redirectToRoute($checkRole,[],302);
        if($request->request->get("action"))
        {
            $orderService->pay($request->request->get('id'));
            return $this->json("done");
        }
        $orders =  $orderRepository->findBy([],["orderAt"=>"ASC","medic"=>"ASC"]);
        return $this->render('lsms/order/index.html.twig', [
            'orders'=>$orders
        ]);
    }
}
