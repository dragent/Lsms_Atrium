<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {    
        if($this->isGranted("ROLE_USER"))
        {
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->set('warning', "Vous n'avez pas les autorisations pour acceder à cette page"); 
            if($this->isGranted("ROLE_STAGIAIRE"))
                return $this->redirectToRoute('app_lsms_index');
        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
