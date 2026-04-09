<?php
namespace App\Twig\Components;

use App\Enum\HouseStatus;
use App\Entity\House;
use App\Service\HouseLoader;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class HouseContainer {

    public function __construct(
        private EntityManagerInterface $em,
        private HouseLoader $hl
    ) {}
    
    use DefaultActionTrait;

    # Indicates the status to show. If null, show all houses
    # regardless of status.
    #[LiveProp]
    public ?HouseStatus $statusQuery = null;

    # Functions to change the query value
    #[LiveAction]
    public function queryApproved(): void {
        $this->statusQuery = HouseStatus::APPROVED;
    }
    #[LiveAction]
    public function queryPending(): void {
        $this->statusQuery = HouseStatus::PENDING;
    }
    #[LiveAction]
    public function queryRejected(): void {
        $this->statusQuery = HouseStatus::REJECTED;
    }
    #[LiveAction]
    public function queryAll(): void {
        $this->statusQuery = null;
    }

    /**
     * This function loads the houses from the database and
     * filters them based on the query.
     * return array The houses that match the query.
     **/
    public function getHouses(): array {
        # Get houses
        return $this->hl->getHouses($this->statusQuery);
    }
    
}
