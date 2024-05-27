<?php

namespace App\Service;

use DateTimeZone;
use App\Entity\Order;
use DateTimeImmutable;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    private EntityManagerInterface $em;
    private OrderRepository $orderRepository;

    private ComptabilityService $comptalityService;

    public function __construct(EntityManagerInterface $em, OrderRepository $orderRepository, ComptabilityService $comptabilityService)
    {
        $this->em =$em;
        $this->orderRepository = $orderRepository;
        $this->comptalityService = $comptabilityService;
    }

    /**
     * Commande qui a été reçue
     */
    public function pay(int $idOrder)
    {
        /**  @var Order */
        $order = $this->orderRepository->find($idOrder);
        $order->setReceiveAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
        $this->em->persist($order);
        $this->comptalityService->remove($order->getInvoice(),"Reçu d'une commande de ".$order->getInvoice()."$");
    }
}