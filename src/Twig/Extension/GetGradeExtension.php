<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GetGradeExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetGradeExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('grade', [$this, 'getGrade']),
        ];
    }

    public function getGrade(array $roles):string
    {       
        if (in_array("ROLE_DIRECTION", $roles))
        return "Directeur";
        if (in_array("ROLE_CHEF_SERVICE", $roles))
            return "Chef de Service";
        if (in_array("ROLE_MEDECIN_CHEF", $roles))
            return "Médecin en chef";
        if (in_array("ROLE_MEDECIN_FORMATEUR", $roles))
            return "Médecin Formateur";
        if (in_array("ROLE_MEDECIN_NOVICE", $roles))
            return "Médecin Novice";
        if (in_array("ROLE_AMBULANCIER", $roles))
            return "Ambulancier";
        return "Stagiaire";
    }
    
}
