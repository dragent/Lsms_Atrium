<?php

namespace App\Service;

use App\Entity\Partner;
use App\Entity\User;
use App\Repository\PartnerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService{

    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    
    public function __construct(EntityManagerInterface $em, UserRepository $partnerRepository)
    {
        $this->userRepository= $partnerRepository;
        $this->em = $em;
    }

    private function objectsToArray(array $users){
        $usersArray=[];
        /** @var User */
        foreach ($users as $user) {
            $value = [ 'id'=>$user->getId(),'name'=>$user->getUsername()];
            array_push($usersArray,$value);
        }
        return $usersArray;

    }

    public function getDoctors()
    {
        $users = $this->userRepository->findByRole("ROLE_LSMS",["column"=>"Username","order"=>"ASC"]);
        return $this->objectsToArray($users);
    }
}