<?php
namespace App\Twig\Components;

use App\Entity\House;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class HouseListing {
    use DefaultActionTrait;

    #[LiveProp(writable: ['status'])]
    public House $house;
    
}
