<?php

namespace App\Controller\Staff\CategoryHealth;

use App\Entity\CategoryHealth;
use App\Service\ConnectService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryHealthRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Form\Staff\CategoryHealth\AddCategoryHealthType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddController extends AbstractController
{
    #[Route('/admin/categorie-soins/ajout', name: 'app_staff_category_health_add')]
    public function index(Request $request, ConnectService $connectService, CategoryHealthRepository $healthCategoryRepository, EntityManagerInterface $em): Response
    {
        /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        $healthCategory = new CategoryHealth();
        $form = $this->createForm(AddCategoryHealthType::class, $healthCategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $name = $healthCategory->getName();
            /** @var Session */
            $session=$request->getSession();
            if($healthCategoryRepository->findOneBy(["slug"=>$name]) === null)
            {
                if($healthCategoryRepository->getLastPosition()==null)
                   $healthCategory->setPosition(0);
                else
                    $healthCategory->setPosition($healthCategoryRepository->getLastPosition()+1);
                $healthCategory->setSlug(strtolower(str_replace(" ","-",$name)));
                $em->persist($healthCategory);
                $em->flush();
                $session->getFlashBag()->set('success', "La catégorie de soin ".$name." a bien été ajoutée");
            }
            else
            {
                $session->getFlashBag()->set('danger', "La catégorie de soin    ".$name." existe déjà");
            }
            
            if($request->get("action")=="save")
            {
                return $this->redirectToRoute("app_staff_category_healh");
            }
        }
        return $this->render('staff/category_health/add/index.html.twig', [
            'form'=>$form,
            'titleBis'=>'Ajout d\'une nouvelle catégorie'
        ]);
    }
}
