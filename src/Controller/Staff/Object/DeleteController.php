<?php

namespace App\Controller\Staff\Object;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/staff/object/delete/php', name: 'app_staff_object_delete_php')]
    public function index(): Response
    {
        return $this->render('staff/object/delete_php/index.html.twig', [
            'controller_name' => 'deletePhpController',
        ]);
    }
}
