<?php
namespace App\Twig\Components;

use App\Entity\House;
use App\Enum\HouseStatus;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class HouseListing {

    public function __construct(
        private EntityManagerInterface $em
    ) {}
    
    use DefaultActionTrait;

    #[LiveProp(updateFromParent: true)]
    public House $house;

    # House Status Altering Functions
    #[LiveAction]
    public function approveHouse()
    {
        $this->house->setStatus(HouseStatus::APPROVED);
        $this->em->flush();
    }
    #[LiveAction]
    public function rejectHouse()
    {
        $this->house->setStatus(HouseStatus::REJECTED);
        $this->em->flush();
    }
    #[LiveAction]
    public function resetHouseStatus()
    {
        $this->house->setStatus(HouseStatus::PENDING);
        $this->em->flush();
    }
    
}
