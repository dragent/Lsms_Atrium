<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\MedicalReasonExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MedicalReasonExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('reason', [$this, 'getReason']),
        ];
    }

    public function getReason(string $reason):string
    {       
        switch ($reason) {
            case 'medicalVisit':
                return "Visite médicale";
            case 'ppa':
                return "Permis de Port d'Arme";
            case 'testPhy':
                return "Test physique";
            case 'rdvPsy':
                return "Rendez vous psy";
            default:
                return "Autre";
        }
    }
}
