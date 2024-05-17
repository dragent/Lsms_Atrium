<?php

namespace App\Controller\Staff;

use App\Entity\User;
use App\Service\ConnectService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/admin', name: 'app_staff')]
    public function index(Request $request, ConnectService $connectService, UserRepository $userRepository, SerializerInterface $serializer): Response
    {   
        
        /** @var Session */
        $session = $request->getSession();
        $checkRole =  $connectService->checkAdmin($this->getUser(),$session,$this->isGranted('ROLE_STAFF'));
        if( $checkRole!==true ) 
            return $this->redirectToRoute($checkRole,[],302);
        
        if($request->get("action")=="fetch_data")
        {
            $users = $userRepository->findLSMSConnected(["column"=>"Username","order"=>"ASC"]);
            $ids =[];
            $userNames = [];
            $grades = [];
            foreach ($users as $user) {
                array_push($ids,$user->getId());
                $userNames[]=$user->getUsername();
                $grades[]=$user->getRoles();
            }
            return $this->json(["id"=>$ids,"user"=>$userNames, "grade"=>$grades]);
        }
        return $this->render('staff/index/index.html.twig', [
            "users"=>$userRepository->findLSMSConnected(["column"=>"Username","order"=>"ASC"]),
        ]);
    }
}
