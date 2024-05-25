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

    public function __construct(EntityManagerInterface $em, OrderRepository $orderRepository)
    {
        $this->em =$em;
        $this->orderRepository = $orderRepository;
    }

    public function pay(int $idOrder)
    {
        /**  @var Order */
        $order = $this->orderRepository->find($idOrder);
        $order->setReceiveAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
        $this->em->persist($order);
        $this->em->flush();
    }
}