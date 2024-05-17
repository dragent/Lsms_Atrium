<?php

namespace App\Controller\Lsms\CareSheet;

use App\Entity\CareSheet;
use App\Service\ConnectService;
use App\Repository\CareSheetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListingCareSheetController extends AbstractController
{
    #[Route('/lsms/fiche-de-soin/controle', name: 'app_lsms_care_sheet_listing')]
    public function index(Request $request, ConnectService $connectService, CareSheetRepository $careSheetRepository, EntityManagerInterface $em): Response
    {       
         /** @var Session */
        $session = $request->getSession();
        /** @var string | bool */
        $checkRole =  $connectService->checkLsms($this->getUser(),$session,$this->isGranted('ROLE_LSMS'));
        if( $checkRole !== true ) 
            return $this->redirectToRoute($checkRole,[],302);
        if($this->isGranted("ROLE_DIRECTION"))
        $caresheets =  $careSheetRepository->findBy([],["medic"=>"ASC","dateCare"=>"ASC"]);
        else
            $caresheets =  $careSheetRepository->findBy(["medic"=>$this->getUser()],["medic"=>"ASC","dateCare"=>"ASC"]);
            if($request->get("action")=="pay")
            {
                /** @var CareSheet  */
                $caresheet = $careSheetRepository->find($request->get('id'));
                $caresheet->setPaid(true);
                $em->persist($caresheet);
                $em->flush();
                return $this->json("done");
            }
        return $this->render('lsms/care_sheet/listing/index.html.twig', [
            'caresheets'=>$caresheets
        ]);
        
    }
}
