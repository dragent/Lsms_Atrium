<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Entity\Partner;
use App\Entity\CareSheet;
use App\Entity\CareSheetItem;
use App\Repository\CareRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\InputBag;

class CareSheetService{

    private EntityManagerInterface $em;
    private CareRepository $careRepository;
    private PartnerRepository $partnerRepository;
    private ComptabilityService $comptabilityService;

    public function __construct( EntityManagerInterface $em, CareRepository $careRepository, PartnerRepository $partnerRepository, ComptabilityService $comptabilityService)
    {
        $this->em= $em;
        $this->careRepository =$careRepository;
        $this->partnerRepository =$partnerRepository;
        $this->comptabilityService=$comptabilityService;
    }

    /**
     * Enregistre la fiche de soin
     */
    public function saveCareSheet( InputBag $request, User $medic )
    {
        $careSheet = new CareSheet();
        if($request->get('partner'))
        {
            $slug = strtolower(str_replace(" ","-",$request->get('partner')));
            /** @var Partner */
            $partner = $this->partnerRepository->findOneBy(["slug"=>$slug]);
            $partner->addCareSheet($careSheet);
            $this->em->persist($partner);
        }    
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
        $careSheet->setDateCare(new DateTime ("now",new DateTimeZone('Europe/Paris')));
        $careSheet->setMedic($medic);
        $this->saveCareSheetItems( $request,  $careSheet);
    }

    /**
     * Enregistre les soins puis les mets dans la fiche de soin
     */
    private function saveCareSheetItems( InputBag $careItems,  CareSheet $careSheet)
    {
        $total= ($careSheet->isFarAway())?10:0;
        foreach ($careItems->all() as $care => $quantity) {
            $slug = str_replace("quantity-", "", $care);
            if($quantity==="")
                continue;
            $careItem = new CareSheetItem();
            $careItem->setCare($this->careRepository->findOneBy(["slug"=>$slug]));
            $careItem->setQuantity($quantity);
            if($careItem->getCare()->hasComponent())
            {
                $component =  $careItem->getCare()->getComponent();
                $component->setQuantity($component->getQuantity()-$quantity);
                $this->em->persist($component);

            }
            $careSheet->addCareSheetItem($careItem);
            $this->em->persist($careItem);
            $total+=$quantity*$careItem->getCare()->getPrice();
        }
        $careSheet->setInvoice($total);
        $this->em->persist($careSheet);
        if($careSheet->isPaid())
            $this->comptabilityService->add($total,"Paiment d'une facture de ".$total."$");
        else
            $this->em->flush();
    }
}