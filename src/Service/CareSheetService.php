<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Entity\CareSheet;
use App\Entity\CareSheetItem;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

class CareSheetService{

    /**
     * Enregistre la fiche de soin
     */
    public function saveCareSheet(EntityManagerInterface $em, InputBag $request, CareRepository $careRepository, User $medic, $partnerRepository )
    {
        $careSheet = new CareSheet();
        $careSheet->setPartner($request->get("partner"));
        $request->remove("partner");
        if( $request->has("distance"))
        {
            $careSheet->setFarAway(true);
            $request->remove("distance");
        }
        else
            $careSheet->setFarAway(false);
        if( $request->has("isPaid"))
        {
            $careSheet->setPaid(true);
            $request->remove("isPaid");
        }
        else
            $careSheet->setPaid(false);
        $careSheet->setDateCare(new DateTime("now",new DateTimeZone('Europe/Paris')));
        $careSheet->setMedic($medic);
        $this->saveCareSheetItems($em, $request, $careRepository, $careSheet);
    }

    /**
     * Enregistre les soins puis les mets dans la fiche de soin
     */
    private function saveCareSheetItems(EntityManagerInterface $em, InputBag $careItems, CareRepository $careRepository, CareSheet $careSheet)
    {
        $total= ($careSheet->isFarAway())?10:0;
        foreach ($careItems->all() as $care => $quantity) {
            $slug = str_replace("quantity-", "", $care);
            if($quantity==="")
                continue;
            $careItem = new CareSheetItem();
            $careItem->setCare($careRepository->findOneBy(["slug"=>$slug]));
            $careItem->setQuantity($quantity);
            $careSheet->addCareSheetItem($careItem);
            $em->persist($careItem);
            $total+=$quantity*$careItem->getCare()->getPrice();
        }
        $careSheet->setInvoice($total);
        $em->persist($careSheet);
        $em->flush();
    }
}