<?php

namespace App\Controller\Staff\Object;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModifyController extends AbstractController
{
    #[Route('/staff/object/{slug}', name: 'app_staff_object_modify')]
    public function index(): Response
    {    /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        return $this->render('staff/object/modify_php/index.html.twig', [
            'controller_name' => 'modifyPhpController',
        ]);
    }
}
