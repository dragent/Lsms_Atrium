<?php

namespace App\Controller\Staff\Care;

use App\Entity\Care;
use App\Form\Staff\Care\AddCareType;
use App\Repository\CareRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AddCareController extends AbstractController
{
    #[Route('/admin/soin/ajout', name: 'app_staff_care_add')]
    public function index(Request $request, ConnectService $connectService, CareRepository $careRepository, EntityManagerInterface $em): Response
    {
         /** @var Session */
         $session = $request->getSession();
         /** @var string | bool */
         $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
         if( $checkRole !== true ) 
             return $this->redirectToRoute($checkRole,[],302);
         $care = new Care();
         $form = $this->createForm(AddCareType::class, $care);
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid())
         { 
             $name = $care->getName();
             /** @var Session */
             $session=$request->getSession();
             if($careRepository->findOneBy(["slug"=>$name]) === null)
             {
                 $care->setSlug(strtolower(str_replace(" ","-",$name)));
                 $em->persist($care);
                 $em->flush();
                 $session->getFlashBag()->set('success', "Le soin ".$name." a bien été ajoutée");
             }
             else
             {
                 $session->getFlashBag()->set('danger', "Le soin ".$name." existe déjà");
             }
             
             if($request->get("action")=="save")
             {
                 return $this->redirectToRoute("app_staff_care");
             }
         }
         return $this->render('staff/care/add_care/index.html.twig', [
            'form'=>$form,
            'titleBis'=>'Ajout de nouveau soin'
        ]);
    }
}
