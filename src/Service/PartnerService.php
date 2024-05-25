<?php

namespace App\Service;

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

}