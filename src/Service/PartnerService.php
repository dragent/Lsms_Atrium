<?php

namespace App\Service;

use App\Entity\Partner;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;

class PartnerService{

    private PartnerRepository $partnerRepository;
    private EntityManagerInterface $em;
    
    public function __construct(EntityManagerInterface $em, PartnerRepository $partnerRepository)
    {
        $this->partnerRepository= $partnerRepository;
        $this->em = $em;
    }

    public function add(string $name)
    {
        $partner = new Partner();
        $partner->setName(strtoupper($name));
        $partner->setSlug(strtolower(str_replace(" ","-",$name)));
        $this->em->persist($partner);
        $this->em->flush();
    }

    public function remove(int $id)
    {
        $partner = $this->partnerRepository->find($id);
        $this->em->remove($partner);
        $this->em->flush();
    }
}