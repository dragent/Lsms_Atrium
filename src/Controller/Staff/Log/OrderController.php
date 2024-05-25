<?php

namespace App\Controller\Staff\Log;

use App\Service\ConnectService;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/admin/commandes', name: 'app_staff_log_order')]

    public function index(OrderRepository $orderRepository, Request $request, ConnectService $connectService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $orders =  $orderRepository->findBy([],["orderAt"=>"ASC","medic"=>"ASC"]);
        return $this->render('staff/log/order/index.html.twig', [
            'orders'=>$orders
        ]);
    }
}
