<?php

namespace App\Controller\Staff\Object;

use App\Entity\Objects;
use App\Form\AddObjectType;
use App\Service\ConnectService;
use App\Repository\ObjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddController extends AbstractController
{
    #[Route('/admin/inventaire/ajout', name: 'app_staff_object_add')]
    public function index(Request $request, ConnectService $connectService, ObjectsRepository $objectsRepository, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(), $session);
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);

        $object = new Objects();
        $form = $this->createForm(AddObjectType::class, $object);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $name = $object->getName();
            $slug = strtolower(str_replace(" ","-",$name));
            /** @var Session */
            $session=$request->getSession();
            if($objectsRepository->findOneBy(["slug"=>$slug]) === null)
            {
                $object->setSlug($slug);
                $em->persist($object);
                $em->flush();
                $session->getFlashBag()->set('success', "Le produit ".$name." a bien été ajouté");
            }
            else
            {
                $session->getFlashBag()->set('danger', "Le produit ".$name." existe déjà");
            }
           
            if($request->get("action")=="save")
            {
                return $this->redirectToRoute("app_staff_object");
            }
        }
        return $this->render('staff/object/add/index.html.twig', [
            'form'=>$form
        ]);
    }
}
