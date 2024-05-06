<?php

namespace App\Controller\Staff\Staff;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListController extends AbstractController
{
    #[Route('/staff/staff/list', name: 'app_staff_staff_list')]
    public function index(): Response
    {
        return $this->render('staff/staff/list/index.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }
}
