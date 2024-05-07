<?php

namespace App\Controller\Staff\Chamber;

use App\Entity\Chamber;
use App\Form\AddChamberType;
use App\Repository\ChamberRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddChamberController extends AbstractController
{
    #[Route('/admin/chambre/ajout', name: 'app_staff_chamber_add')]
    public function index(Request $request, ConnectService $connectService, EntityManagerInterface $em, ChamberRepository $chamberRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $chamber = new Chamber();
        $form = $this->createForm(AddChamberType::class, $chamber);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $name = $chamber->getName();
            /** @var Session */
            $session=$request->getSession();
            if($chamberRepository->findOneBy(["slug"=>$name]) === null)
            {
                $chamber->setSlug($name);
                $em->persist($chamber);
                $em->flush();
                $session->getFlashBag()->set('success', "La chambre ".$name." a bien été ajoutée");
            }
            else
            {
                $session->getFlashBag()->set('danger', "La chambre ".$name." existe déjà");
            }
           
            if($request->get("action")=="save")
            {
                return $this->redirectToRoute("app_staff_chamber");
            }
        }
        return $this->render('staff/chamber/add_chamber/index.html.twig', [
            'form'=>$form
        ]);
    }

    
}
