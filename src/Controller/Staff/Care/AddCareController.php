<?php

namespace App\Controller\Staff\Care;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddCareController extends AbstractController
{
    #[Route('/staff/care/add/care', name: 'app_staff_care_add_care')]
    public function index(): Response
    {
        return $this->render('staff/care/add_care/index.html.twig', [
            'controller_name' => 'AddCareController',
        ]);
    }
}
