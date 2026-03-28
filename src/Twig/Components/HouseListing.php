<?php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class HouseListing {
    public string $title = 'House';
    public int $monthlyRent = 0;
    public string $city = 'Amsterdam';
}
