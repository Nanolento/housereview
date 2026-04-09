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

    # updateFromParent here makes it so the filters cause a
    # re-render on this component, causing it to show the
    # correct information.
    #[LiveProp(updateFromParent: true)]
    public House $house;

    # House Status Altering Functions
    #[LiveAction]
    public function approveHouse(): void {
        $this->house->setStatus(HouseStatus::APPROVED);
        $this->em->flush();
    }
    #[LiveAction]
    public function rejectHouse(): void {
        $this->house->setStatus(HouseStatus::REJECTED);
        $this->em->flush();
    }
    #[LiveAction]
    public function resetHouseStatus(): void {
        $this->house->setStatus(HouseStatus::PENDING);
        $this->em->flush();
    }
    
}
