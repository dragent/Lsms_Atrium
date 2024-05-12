<?php

namespace App\Controller\Staff\Care;

use App\Service\ConnectService;
use App\Repository\CareRepository;
use App\Form\Staff\Care\ModifyCareType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyCareController extends AbstractController
{
    #[Route('/admin/soin/{slug}', name: 'app_staff_care_modify')]
    public function index(string $slug, Request $request, ConnectService $connectService, CareRepository $careRepository, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $care=$careRepository->findOneBy(["slug"=>$slug]);
        if($care===null)
        {
            $name = ucfirst(str_replace("-"," ",$slug));
            $session->getFlashBag()->set('danger', "Le soin ".$name." n'est pas pratiqué");
            return $this->redirectToRoute('app_staff_care',[],302);
        }
        $form = $this->createForm(ModifyCareType::class, $care);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($care);
            $em->flush();
            $session->getFlashBag()->set('success', "Le soin ".$care->getName()." a bien été modifié");
            return $this->redirectToRoute('app_staff_care');
        }
        return $this->render('staff/care/modify_care/index.html.twig', [
            'form'=>$form,
            'care'=>$care
        ]);
    }
}
