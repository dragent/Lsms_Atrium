<?php

namespace App\Controller\Staff\Object;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AddController extends AbstractController
{
    #[Route('/admin/inventaire/ajout', name: 'app_staff_object_add')]
    public function index(Request $request, ConnectService $connectService): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole != true ) 
            return $this->redirectToRoute($checkRole);

        
        return $this->render('staff/object/add_php/index.html.twig', [
            'controller_name' => 'addPhpController',
        ]);
    }
}
