<?php 

namespace App\Model;

use Symfony\Component\Validator\Constraints\Length;

Class DiscordUser
{
    private const ROLES= ['1233050423988977754'=>'ROLE_LSMS','1233049148127969393'=>"ROLE_CHEF_SERVICE",'1235142249134686238'=>"ROLE_MEDECIN_CHEF",'1233050078663413831'=>'ROLE_MEDECIN_FORMATEUR','1233050223992115260'=>"ROLE_MEDECIN_NOVICE",'1233050812771733575'=>"ROLE_AMBULANCIER","1233050490900840518"=>"ROLE_STAGIAIRE","1233049958593069136"=>"ROLE_STAFF","1233049427107647488"=>"ROLE_FONDA","1233050742919794709"=>"ROLE_CIVIL"]; 
    private string $id;
    private string $username;
    private string $email;
    private string $avatar;
    private array $roles;

    public function getId():string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id=$id;
    }
    public function getUsername():string
    {
        return $this->username;
    }

    public function setUsername(?string $username)
    {
        if($username!=null)
        $this->username=$username;
    }
    public function getEmail():string
    {
        return $this->email;
    }
    public function setAvatar(string $avatar)
    {
        $this->avatar=$avatar;
    }
    public function getAvatar():string
    {
        return $this->avatar;
    }

    public function setEmail(string $email)
    {
        $this->email=$email;
    }
    public function getRoles():array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        if(sizeof($roles)==0)
        {
            $this->roles[]="ROLE_USER";
            return;
        }
        foreach($roles as $key=>$role)
        {
            $this->roles[]= self::ROLES[$role];
        }
    }
}