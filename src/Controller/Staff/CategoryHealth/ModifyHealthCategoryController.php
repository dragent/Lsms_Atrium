<?php

namespace App\Controller\Staff\CategoryHealth;

use App\Entity\CategoryHealth;
use App\Repository\CategoryHealthRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifyHealthCategoryController extends AbstractController
{
    #[Route('/admin/categorie-soins/modification', name: 'app_staff_category_health_modify')]
    public function index(CategoryHealthRepository $categoryHealthRepository, Request $request, EntityManagerInterface $em): Response
    {   
        if($this->getUser()===null)
            return $this->json("Vouse devez être connectés");
        
        if(!$this->isGranted('ROLE_STAFF'))
            return $this->json("Vous n'avez pas le bon role");

        if($request->get("action")=="update")
        {
            $count= 0;
            $rows = $request->get('position');
            for($count;$count < sizeof($rows);$count++)
            {
                /** @var CategoryHealth  */
                $category = $categoryHealthRepository->find($rows[$count]);
                $category->setPosition($count);
                $em->persist($category);
            }
            $em->flush();
            return $this->json("Vous avez mis à jour la bdd");   
        }
        
        if($request->get("action")=="fetch_data")
            return $this->json($categoryHealthRepository->findBy([],["position"=>"ASC"]));   
    }
}
