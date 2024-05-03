<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    
    #[Route("/deconnexion",name:'app_logout')]
    public function logout(Request $request)
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->set('warning', "Vous n'avez pas les autorisations pour acceder à cette page");
            return $this->redirectToRoute('app_index');
        }
        throw new \Exception();
    }
}
