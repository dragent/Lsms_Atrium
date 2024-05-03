<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\DiscordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscordController extends AbstractController
{
    private const DISCORD_AUTH_KEY = "discord-auth";
    public function __construct(
        private readonly DiscordService $discordApiService
    )
    {
        
    }

    /**
     * Check if there is user is already connected
     * if yes 
     *      go back to index
     * Else
     *      check if token is correct
     *      if yes
     *          send request to discord api for connect
     *      Else
     *      go back to index
     */
    #[Route('/discord/connect', name: 'app_discord_connect')]
    public function connect(Request $request): Response
    {  
        if($this->isGranted("ROLE_USER"))
        {
            $user = $this->getUser();
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->set('warning', "Vous n'avez pas les autorisations pour acceder à cette page"); 
            if(in_array("ROLE_STAGIAIRE",$user->getRoles()))
                return $this->redirectToRoute('app_index');
            if(in_array("ROLE_STAFF",$user->getRoles()))
                return $this->redirectToRoute('app_admin_index');
            if(in_array("ROLE_CIVIL",$user->getRoles()))
                return $this->redirectToRoute('app_index');
        }
        $token=$request->get('token');
        if($this->isCsrfTokenValid(self::DISCORD_AUTH_KEY,$token))
        {
            $request->getSession()->set(self::DISCORD_AUTH_KEY,true);
            $scope=['identify','email','guilds','guilds.join','guilds.members.read'];
            return $this->redirect($this->discordApiService->getAuthorizationURL($scope));
        }
        return $this->redirectToRoute('app_index');
    }

    /**
     * Check if user is connected
     * if yes 
     *      go back to index
     * Get information for getting token from bot connection with discord api
     * Get Information of user
     * Check if user has the discord created for this coding
     * If no 
     *      Get User information from the discord after joining it
     * Else
     *      Get user information from the discord
     * Set the information from discord on the model
     * Check if user exist
     * If no
     *      Create it
     * Else 
     *      Modify information if different from databases
     * go for the authentification
     * 
     */
    #[Route('/connexion/check', name: 'app_discord_check')]
    public function check(Request $request, EntityManagerInterface $em, UserRepository $userRepo)
    {  
        if($this->isGranted("ROLE_USER"))
        {
            $user = $this->getUser();
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->set('warning', "Vous n'avez pas les autorisations pour acceder à cette page"); 
            if(in_array("ROLE_STAGIAIRE",$user->getRoles()))
                return $this->redirectToRoute('app_index');
            return $this->redirectToRoute('app_index');
        }

        $code = $request->query->get('code');
        $botAuthorize = $this->discordApiService->authorizeBot($code);
        $accessToken=$botAuthorize['access_token'];
        $discordUser=$this->discordApiService->fetchUser($accessToken);
        if(!$this->discordApiService->userHasDiscordServ($accessToken))
        {
            $nickname=$this->discordApiService->atriumGetUser($accessToken);
            $discordInfo= $this->discordApiService->joinDiscord($accessToken, $discordUser, $nickname);
        }
        else
        {
            $discordInfo=$this->discordApiService->discordGetInfo($accessToken);
        }
        
        $discordUser->setRoles($discordInfo["roles"]);
        $discordUser->setUsername($discordInfo["nick"]);
        /** @var User */
        $user = $userRepo->findOneBy(['discordId'=>$discordUser->getId()]);
        if($user === null)
        {
           $user = new User();
           $user->setAccessToken($accessToken);
           $user->setRoles($discordUser->getRoles());
           $user->setDiscordId($discordUser->getId());
           $user->setEmail($discordUser->getEmail());
           $user->setUsername($discordUser->getUsername());
        } 
        else
        {
            if($user->getEmail()!=$discordUser->getEmail())
                $user->setEmail($discordUser->getEmail());
            if($user->getUsername()!=$discordUser->getUsername())
                $user->setUsername($discordUser->getUsername());
            if(!empty(array_merge(array_diff($discordUser->getRoles(), $user->getRoles()),array_diff($user->getRoles(), $discordUser->getRoles()))))
            {
                $user->setRoles($discordUser->getRoles());
            }
        }
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_discord_auth',[
            'accessToken'=>$accessToken,
        ]);
    }

    /**
     * Path for success authentification
     */
    #[Route('/connexion/auth', name: 'app_discord_auth')]
    public function auth()
    {
            return $this->redirectToRoute('app_index');
    }
}
