<?php

namespace App\Controller\Staff\Chamber;

use App\Repository\ChamberRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteChamberController extends AbstractController
{
    #[Route('/admin/chambre/suppression/{slug}', name: 'app_staff_chamber_delete')]
    public function index(string $slug, Request $request, ConnectService $connectService, ChamberRepository $chamberRepository, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        $chamber = $chamberRepository->findOneBy(["slug"=>$slug]);
        $name = $slug;
        if($chamber===null)
            $session->getFlashBag()->set('danger', "La chambre ".$name." n'a jamais été recensée");
        else{
            $em->remove($chamber);
            $em->flush();
            $session->getFlashBag()->set('success', "La chambre ".$name." a bien été fermée"); 
        }
        return $this->redirectToRoute('app_staff_chamber',[],302);

    }
}
