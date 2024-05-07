<?php

namespace App\Controller\Staff\Object;

use App\Repository\ChamberRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteController extends AbstractController
{
    #[Route('/admin/inventaire/supprimer/{slug}', name: 'app_staff_object_delete')]
    public function index(Request $request, ConnectService $connectService, ChamberRepository $chamberRepository, EntityManagerInterface $em, string $slug): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        $object = $chamberRepository->findOneBy(["slug"=>$slug]);
        $name = ucfirst(str_replace("-"," ",$slug));
        if($object===null)
            $session->getFlashBag()->set('danger', "La chambre ".$name." n'a jamais été recensée");
        else{
            $em->remove($object);
            $em->flush();
            $session->getFlashBag()->set('success', "La chambre ".$name." a bien été recensée"); 
        }
        return $this->redirectToRoute('app_staff_object',[],302);
    }
}
