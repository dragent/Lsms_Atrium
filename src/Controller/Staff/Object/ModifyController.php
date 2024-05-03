<?php

namespace App\Controller\Staff\Object;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModifyController extends AbstractController
{
    #[Route('/staff/object/modify/php', name: 'app_staff_object_modify_php')]
    public function index(): Response
    {
        return $this->render('staff/object/modify_php/index.html.twig', [
            'controller_name' => 'modifyPhpController',
        ]);
    }
}
