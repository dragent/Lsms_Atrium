<?php

namespace App\Controller\Staff\Object;

use App\Service\ConnectService;
use App\Repository\ObjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteController extends AbstractController
{
    #[Route('/admin/inventaire/supprimer/{slug}', name: 'app_staff_object_delete')]
    public function index(Request $request, ConnectService $connectService, ObjectsRepository $objectsRepository, EntityManagerInterface $em, string $slug): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        $object = $objectsRepository->findOneBy(["slug"=>$slug]);
        $name = ucfirst(str_replace("-"," ",$slug));
        if($object===null)
            $session->getFlashBag()->set('danger', "Le produit ".$name." n'existe pas");
        else{
            $em->remove($object);
            $em->flush();
            $session->getFlashBag()->set('success', "Le produit ".$name." a été supprimé");
            
        }
        return $this->redirectToRoute('app_staff_object',[],302);
    }
}
