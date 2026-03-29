<?php
namespace App\Twig\Components;

use App\Enum\HouseStatus;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class HouseContainer {    
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?HouseStatus $statusQuery = null;

    
}
