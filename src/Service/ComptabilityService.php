<?php

namespace App\Service;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\Transaction;
use App\Entity\Comptability;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ComptabilityRepository;

class ComptabilityService{

    private Comptability|Null $comptability;
    private EntityManagerInterface $em;

    public function __construct(ComptabilityRepository $comptabilityRepository, EntityManagerInterface $em){
        $this->comptability=$comptabilityRepository->find(1);
        $this->em= $em;
    }

    /**
     * Annonce une rentrée d'argent
     */
    public function add(int $invoice, string $reason)
    {
        $transaction = new Transaction();
        $transaction->setDoneAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
        $transaction->setIncome(true);
        $transaction->setPrice($invoice);
        $transaction->setReason($reason);
        $this->comptability->setTreasury($this->comptability->getTreasury()+$invoice);
        $this->comptability->addTransaction($transaction);
        $this->em->persist($transaction);
        $this->em->persist($this->comptability);
        $this->em->flush();
    }

    /**
     * Annonce un retrait d'argent
     */
    public function remove(int $invoice, string $reason)
    {
        $transaction = new Transaction();
        $transaction->setDoneAt(new DateTimeImmutable("now",new DateTimeZone('Europe/Paris')));
        $transaction->setIncome(false);
        $transaction->setPrice($invoice);
        $transaction->setReason($reason);
        $this->comptability->setTreasury($this->comptability->getTreasury()-$invoice);
        $this->comptability->addTransaction($transaction);
        $this->em->persist($transaction);
        $this->em->persist($this->comptability);
        $this->em->flush();
    }
}