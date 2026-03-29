<?php
namespace App\Twig\Components;

use App\Enum\HouseStatus;
use App\Entity\House;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent]
class HouseContainer {

    public function __construct(
        private EntityManagerInterface $em
    ) {}
    
    use DefaultActionTrait;

    #[LiveProp]
    public ?HouseStatus $statusQuery = null;

    # Functions to change the query value
    #[LiveAction]
    public function queryApproved() {
        $this->statusQuery = HouseStatus::APPROVED;
    }
    #[LiveAction]
    public function queryPending() {
        $this->statusQuery = HouseStatus::PENDING;
    }
    #[LiveAction]
    public function queryRejected() {
        $this->statusQuery = HouseStatus::REJECTED;
    }
    #[LiveAction]
    public function queryAll() {
        $this->statusQuery = null;
    }

    /**
     * This function does the filtering based on the query.
     * return array The houses that match the query.
     **/
    public function getHouses() {
        $repo = $this->em->getRepository(House::class);

        # If there is no query, just get all of them.
        if ($this->statusQuery === null) {
            return $repo->findAll();
        } else {
            return $repo->findBy([
                'status' => $this->statusQuery->value,
            ]);
        }
    }
    
}
