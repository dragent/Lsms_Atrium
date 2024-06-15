<?php

namespace App\Controller\Lsms\Course;

use App\Service\ConnectService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MedicalFolderController extends AbstractController
{
    #[Route('/lsms/dossier-medeicaux', name: 'app_lsms_course_medical_folder')]
    public function index(Request $request, ConnectService $connectService): Response
    {   
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        return $this->render('lsms/course/medical_folder/index.html.twig', [
        ]);
    }
}
