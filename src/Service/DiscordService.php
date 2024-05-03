<?php

namespace App\Service;

use App\Model\DiscordUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiscordService {


    /**
     * Configuration des constantes publiques
     */
    private const DISCORD_LSMS_ID ="1233049081236951221";
    private const DISCORD_ATRIUM_ID ="1018870828919685262";
    const AUTHORIZATION_URI = "https://discord.com/api/oauth2/authorize";
    const TOKEN_URI = "https://discord.com/api/oauth2/token";
    const USERS_ME_ENDPOINT ="https://discord.com/api/users/@me";
    const LSMS_USER_ME_ENDPOINT ="https://discord.com/api/users/@me/guilds/".self::DISCORD_LSMS_ID."/member";
    const ATRIUM_USER_ME_ENDPOINT ="https://discord.com/api/users/@me/guilds/".self::DISCORD_ATRIUM_ID."/member";
    const LIST_DISCORD_USER = "https://discord.com/api/users/@me/guilds";
    const DISCORD_JOIN_USER = "/api/guilds/".self::DISCORD_LSMS_ID."/members/";
    const ROLE_CIVIL ="/roles/1233050742919794709";

    /**
     * Importation des variables personnelles
     */
        public function __construct(
        private readonly HttpClientInterface $discordApiClient,
        private readonly SerializerInterface $serializer,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUri,
        private readonly string $botToken ){

    }

    /**
     * Prepare the query for authorization from discord
     */
    public function getAuthorizationURL(array $scope): string
    {
       /*;*/
        $queryParameters = http_build_query([
            'client_id'=> $this->clientId,
            'client_secret'=> $this->clientSecret,
            'redirect_uri'=>$this->redirectUri, 
            'response_type'=>'code',
            'scope'=>implode(' ',$scope)
        ]);
        return self::AUTHORIZATION_URI.'?'.$queryParameters;
        
    }

    /**
     * Get user from discord and put information on the model discordUser
     */
    public function fetchUser(string $accessToken): DiscordUser
    {
        $response= $this->discordApiClient->request(Request::METHOD_GET, self::USERS_ME_ENDPOINT,[
            'auth_bearer'=>$accessToken,
        ]);
        $user = $response->getContent(); 
        return $this->serializer->deserialize($user, DiscordUser::class, 'json');
    }

    /**
     * Get information of our user on discord
     */
    public function discordGetInfo(string $accessToken)
    {
        $response= $this->discordApiClient->request(Request::METHOD_GET, self::LSMS_USER_ME_ENDPOINT,[
            'auth_bearer'=>$accessToken,
        ]);
        $discord_read = $response->getContent(); 
        $discord_read = json_decode($discord_read,true);
        return $discord_read;
    }

    /**
     * Ask api the list of server of the user
     * Check if ours is within
     */
    public function userHasDiscordServ( string $accessToken  ): int|bool
    {
        $response= $this->discordApiClient->request(Request::METHOD_GET, self::LIST_DISCORD_USER,[
            'auth_bearer'=>$accessToken,
        ]);
        $discord_list = $response->getContent();
        $discord_list = json_decode($discord_list);
        foreach ($discord_list as $key => $value) {
            $discord_server = json_decode(json_encode($value), true);
            if ($discord_server['id']===self::DISCORD_LSMS_ID)
                return true; 
        }
        return false;
    }

    /**
     * Make the user join the discord
     */
    public function joinDiscord( string $accessToken , DiscordUser $discordUser, string $atriumNick)
    {
        $payload = [
            'access_token'=>$accessToken,
            'nick'=>$atriumNick,
            'roles'=>['1233050742919794709']
        ];
    
        $discord_api_url = "https://discord.com".SELF::DISCORD_JOIN_USER.$discordUser->getId();
        $header = array("Authorization: Bot $this->botToken", "Content-Type: application/json");
    
        $result = $this->discordApiClient->request(Request::METHOD_PUT, $discord_api_url,[
            "headers"=>$header,
            "body"=>json_encode($payload)
        ]);
        return json_decode($result->getContent(),true);
    }

    /**
     * Set all variable and connect to the bot
     */
    public function authorizeBot(string $code): array
    {

        $queryParameters = http_build_query([
            'client_id'=> $this->clientId,
            'client_secret'=>$this->clientSecret,
            'redirect_uri'=>$this->redirectUri, 
            'grant_type'=>'authorization_code',
            'code'=>$code
        ]);
        $response= $this->discordApiClient->request(Request::METHOD_POST, self::TOKEN_URI,[
            'body'=>$queryParameters,
        ]);
        return json_decode($response->getContent(),true);
    }

    /**
     * Get information of our user on discord
     */
    public function atriumGetUser(string $accessToken)
    {
        $response= $this->discordApiClient->request(Request::METHOD_GET, self::ATRIUM_USER_ME_ENDPOINT,[
            'auth_bearer'=>$accessToken,
        ]);
        $discord_read = $response->getContent(); 
        $discord_read = json_decode($discord_read,true);
        return $discord_read["nick"];
    }
    
}