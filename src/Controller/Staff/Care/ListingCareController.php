<?php

namespace App\Controller\Staff\Care;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListingCareController extends AbstractController
{
    #[Route('/staff/care/listing/care', name: 'app_staff_care_listing_care')]
    public function index(): Response
    {
        return $this->render('staff/care/listing_care/index.html.twig', [
            'controller_name' => 'ListingCareController',
        ]);
    }
}
