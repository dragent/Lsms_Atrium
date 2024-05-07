<?php

namespace App\Controller\Staff\Object;

use App\Form\ModifyObjectType;
use App\Repository\ObjectsRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyController extends AbstractController
{
    #[Route('/admin/inventaire/{slug}', name: 'app_staff_object_modify')]
    public function index(Request $request, ConnectService $connectService, ObjectsRepository $objectsRepository, EntityManagerInterface $em, string $slug): Response
    {    /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);

        $object=$objectsRepository->findOneBy(["slug"=>$slug]);
        if($object===null)
        {
            $name = ucfirst(str_replace("-"," ",$slug));
            $session->getFlashBag()->set('danger', "Le produit ".$name." n'existe pas");
            return $this->redirectToRoute('app_staff_object',[],302);
        }
        $form = $this->createForm(ModifyObjectType::class, $object);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($object);
            $em->flush();
            return $this->redirectToRoute('app_staff_object');
        }
        return $this->render('staff/object/modify/index.html.twig', [
            'object' => $object,
            'form'=>$form
        ]);
    }
}
