<?php

namespace App\Controller\Staff\Care;

use App\Repository\CareRepository;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteCareController extends AbstractController
{
    #[Route('/admin/soin/suppression/{slug}', name: 'app_staff_care_delete')]
    public function index(Request $request, ConnectService $connectService,CareRepository $careRepository, string $slug, EntityManagerInterface $em ): Response
    {
      /** @var Session */
      $session = $request->getSession();
      /** @var string | bool */
      $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
      if( $checkRole !== true ) 
          return $this->redirectToRoute($checkRole);
      $care = $careRepository->findOneBy(["slug"=>$slug]);
      $name = $slug;
      if($care===null)
          $session->getFlashBag()->set('danger', "Le soin ".$name." n'a jamais été pratiqué");
      else{
          $em->remove($care);
          $em->flush();
          $session->getFlashBag()->set('success', "Le soin ".$name." a bien été oublier de nos pratiques"); 
      }
      return $this->redirectToRoute('app_staff_care',[],302);
    }
}
