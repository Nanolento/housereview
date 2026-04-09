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

    # Error string set by errors loading the house data.
    public ?string $error = null;

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
     * This function gets the house and does error handling.
     * @return array The houses that match the query.
     **/
    public function getHouses(): array {
        # Get houses
        try {
            $houses = $this->hl->getHouses($this->statusQuery);
            return $houses;
        } catch (PDOException $e) {
            $this->error = "Something went wrong when loading the database.";
            return []; # Return nothing so the component re-renders with nothing.
        } catch (\RuntimeException $e) {
            # this exception is raised when at runtime the site fails to load the json file for parsing
            # either due to a missing file or invalid json being found.
            $this->error = "Something went wrong when parsing the input file containing the house data. ".
                "This could be due to a missing input file or the file not containing valid JSON.";
            return [];
        } catch (\UnexpectedValueException $e) {
            # This exception is raised when loading the houses from the json file, but the json file does not
            # contain house data or the structure is wrong.
            $this->error = "Something went wrong when loading houses from the input file, there are no houses".
                " defined in the JSON data!";
            return [];
        } catch (\InvalidArgumentException $e) {
            $this->error = $e->getMessage();
            return [];
        }
    }
    
}
