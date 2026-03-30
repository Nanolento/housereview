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
     * This function loads the houses from the database and
     * filters them based on the query.
     * return array The houses that match the query.
     **/
    public function getHouses() {
        $repo = $this->em->getRepository(House::class);

        # If there is no query, just get all of them.
        if ($this->statusQuery === null) {
            $houses = $repo->findAll();

            # Since there is no query, this is all houses.
            # If there are no houses, lets try parsing the input data
            # to fill the database.
            if (count($houses) === 0) {
                # Load houses into db if there are none in the db.
                if ($this->hl->loadHouses()) {
                    # Get houses again
                    $houses = $repo->findAll();
                } # else there are no houses, just return the nothing we found earlier.
            }

            return $houses;
        } else {
            # Find house by query
            return $repo->findBy([
                'status' => $this->statusQuery,
            ]);
        }
    }
    
}
