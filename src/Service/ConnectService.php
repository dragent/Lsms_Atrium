<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

Class ConnectService{

    /** Check if user is an admin */
    public function checkAdmin(?User $user, Session $session, bool $isGranted): String|Bool
    { 
        
        if($user === null)
        {
            $session->getFlashBag()->set('warning', "Veuillez vous connecter");
            return 'app_login';
        }
        if(!$isGranted)
        {
            $session->getFlashBag()->set('warning', "Vous n'avez pas les droits pour accéder à cette page");
            if(in_array("ROLE_LSMS",$user->getRoles()))
            {
                return 'app_lsms_index';
            }
            return 'app_index';
        }
        return true;
    }

    /** Check if user the good grade of lsms */
    public function checkLsms(?User $user, Session $session, bool $isGranted): String|Bool
    { 
        
        if($user === null)
        {
            $session->getFlashBag()->set('warning', "Veuillez vous connecter");
            return 'app_login';
        }
        if(!$isGranted)
        {
            $session->getFlashBag()->set('warning', "Vous n'avez pas les droits pour accéder à cette page");
            if(array_search("ROLE_LSMS",$user->getRoles()) === false )
                return 'app_index';
            else    
                return 'app_lsms_index';
        }
        return true;
    }

    
    /** Check if user isn t a lsms member */
    public function checkCivils(?User $user, Session $session, bool $isNotLsms)
    {
        if($user === null)
        {
            $session->getFlashBag()->set('warning', "Veuillez vous connecter");
            return 'app_login';
        }
        if(!$isNotLsms)
        {
            $session->getFlashBag()->set('warning', "Vous n'avez pas les droits pour accéder à cette page");
            return 'app_lsms_index';
        }
        return true;
    }
}