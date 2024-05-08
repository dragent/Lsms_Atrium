<?php

namespace App\Controller\Staff\Chamber;

use App\Form\ModifyChamberType;
use App\Service\ConnectService;
use App\Repository\ChamberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyChamberController extends AbstractController
{
    #[Route('/admin/chambre/{slug}', name: 'app_staff_chamber_modify')]
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
        {
            $session->getFlashBag()->set('danger', "La chambre ".$name." n'a jamais été recensée");
            return $this->redirectToRoute('app_staff_chamber',[],302);
        }
        $form = $this->createForm(ModifyChamberType::class, $chamber);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($chamber);
            $em->flush();
            return $this->redirectToRoute('app_staff_chamber');
        }
        return $this->render('staff/chamber/modify_chamber/index.html.twig', [
            'chamber' => $chamber,
            'form'=>$form,
        ]);
    }
}
