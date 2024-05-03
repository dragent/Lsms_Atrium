<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

Class ConnectService{

    public function checkAdmin(User $user, Session $session): String|Bool
    { 
        if(!in_array("ROLE_STAFF",$user->getRoles()))
        {
            if(in_array("ROLE_STAGIAIRE",$user->getRoles()))
            {
                $session->getFlashBag()->set('warning', "Vous n'avez pas les droits pour accéder à cette page");
                return 'app_index';
            }
            $session->getFlashBag()->set('warning', "Veuillez vous connecter");
            return 'app_index';
        }
        return true;
    }
}