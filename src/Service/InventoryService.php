<?php 

namespace App\Service;

use DateTimeZone;
use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Objects;
use App\Entity\OrderItems;
use App\Repository\ObjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

class InventoryService
{

    public function fabricate(ObjectsRepository $objectsRepository, EntityManagerInterface $em, InputBag $request )
    {
        $request->remove("action");
        foreach ($request->all() as $key => $value) {
            /** @var Objects */
            $object = $objectsRepository->findOneBy(["slug"=>$key]);
            $object->setQuantity($object->getQuantity()+intval($value));
            foreach ($object->getQuantitiesComponent() as $quantity) {
                $component = $quantity->getComponent();
                $component->setQuantity($component->getQuantity()-intval($value)*$quantity->getQuantity());
                $em->persist($component);
            }
            $em->persist($object);
        }
        $em->flush();
    }

    public function Order(ObjectsRepository $objectsRepository, EntityManagerInterface $em, InputBag $request, User $user)
    {
        $request->remove("action");
        $order = new Order();
        $user->addOrder($order);
        $order->setOrderAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
        $invoice = 0;
        foreach ($request->all() as $key => $value) {
            if(intval($value)===0)
                continue;
            /** @var Objects */
            $object = $objectsRepository->findOneBy(["slug"=>$key]);
            $object->setQuantity($object->getQuantity()+intval($value));
            $invoice+= intval($value)*$object->getBuyPrice();
            $em->persist($object);
            $orderObject = new OrderItems();
            $orderObject->setObject($object);
            $orderObject->setQuantity(intval($value));
            $em->persist($orderObject);
            $order->addOrderItem($orderObject);
        }
        $order->setInvoice($invoice);
        $em->persist($order);
        $em->flush();
    }

}