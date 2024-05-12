<?php

namespace App\Controller\Staff\CategoryHealth;

use App\Service\ConnectService;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryHealthRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteHealthCategoryController extends AbstractController
{
    #[Route('/admin/categorie-soins/suppression/{slug}', name: 'app_staff_category_health_delete')]
    public function index(string $slug,Request $request, ConnectService $connectService, CategoryHealthRepository $categoryHealthRepository, EntityManagerInterface $em, CareRepository $careRepository): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole);
        $category = $categoryHealthRepository->findOneBy(["slug"=>$slug]);
        $name = ucfirst(str_replace("-"," ",$slug));
        if($category===null)
            $session->getFlashBag()->set('danger', "La catégorie ".$name." n'a jamais été recensée");
        else{
            $careRepository->deleteCare($category->getId());
            $em->remove($category);
            $em->flush();
            $session->getFlashBag()->set('success', "La catégorie  ".$name." a bien été supprimée"); 
        }
        return $this->redirectToRoute('app_staff_category_healh',[],302);
    }
}
