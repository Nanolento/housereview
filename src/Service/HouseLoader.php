<?php
namespace App\Service;

use App\Entity\House;

use App\Enum\HouseStatus;

use Doctrine\ORM\EntityManagerInterface;

/*
The HouseLoader class takes houses from the JSON data and puts them in the SQL
database. If run when the database is not empty, it will add the houses that were
not yet in the database to the database.
*/

class HouseLoader {

    public function __construct(private string $filePath,
                                private EntityManagerInterface $em,
                                private HouseGrader $houseGrader) {}
    
    /**
     * Get house data from the JSON file.
     * @param string housefilepath The file path to the file containing the JSON data.
     * @return array The house JSON data as an array.
     */
    private function getHouseData(string $housefilepath): array {
        # Read file
        $housefile = file_get_contents($housefilepath);
        if (!$housefile) {
            # Failed loading the file, it perhaps does not exist or failed to read in some way.
            throw new \RuntimeException('Failed to load file: '.$housefilepath);
        }

        # Parse JSON
        $housedata = json_decode($housefile, true);
        if ($housedata === null) {
            # The JSON data parsing failed. Perhaps the file contains invalid JSON?
            throw new \RuntimeException('Failed to parse JSON, is the JSON valid?');
        }

        # Make sure the data is actually an array.
        if (!is_array($housedata)) {
            throw new \UnexpectedValueException('Expected an array from the JSON data, but received another type.');
        }
        return $housedata;
    }

    /**
     * Validates the data from the file is correct and throws
     * InvalidArgumentException's if the data is missing required keys.
     * @param array houses The house data loaded in from the JSON.
     */
    private function validateHouseData(array $houses): void {
        # the loading function already checked if the data was an array.

        # Process each house
        foreach ($houses as $house) {
            # check if the house is an array
            if (!is_array($house)) {
                throw new \UnexpectedValueException('Expected house data to be an array, but received another type.');
            }
            
            # check if all required keys are present
            $required_keys = ['listing_id', 'location_city'];
            foreach ($required_keys as $key) {
                if (!isset($house[$key])) {
                    # This house does not have an ID we can check and is therefore invalid.
                    throw new \InvalidArgumentException("Data is missing a required value ('$key').");
                }
            }
        }
    }

    /**
     * This function checks if the house already exists in the database by checking if a house
     * with this external ID already is in the database.
     * @param string externalId The house's external ID.
     * @return bool If it exists or not.
     */
    private function houseExists(string $externalId) {
        $existingHouse = $this->em->getRepository(House::class)->findOneBy([
            'externalId' => $externalId,
        ]);

        if ($existingHouse) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Helper function to get optional keys from the data or null otherwise.
     * @param array data Data to get the key from.
     * @param string key Key name to get.
     * @return ?int The integer value or null if not found or invalid.
     */
    private function getOptionalInt(array $data, string $key): ?int
    {
        return isset($data[$key]) && is_int($data[$key]) ? $data[$key] : null;
    }

    /**
     * Helper function to get optional keys from the data or null otherwise.
     * @param array data Data to get the key from.
     * @param string key Key name to get.
     * @return ?int The string value or null if not found or invalid.
     */
    private function getOptionalString(array $data, string $key): ?string
    {
        return isset($data[$key]) && is_string($data[$key]) ? $data[$key] : null;
    }

    /**
     * This function takes the JSON array data and turns it into a House object
     * used in the application.
     * @param array housedata The individual house's data as an array, from the JSON.
     * @return House A House object initialized with the given data.
     */
    private function mapToHouse(array $housedata): House {
        # Create house object and set values
        $house = new House();
        $house->setExternalId($housedata['listing_id']);
        # Set optional values.
        $house->setTitle($this->getOptionalString($housedata, 'headline'));
        $house->setMonthlyRent($this->getOptionalInt($housedata, 'monthly_rent'));
        $house->setEnergyLabel($this->getOptionalString($housedata, 'energy_class'));
        # Set city. (already validated)
        $house->setCity($housedata['location_city']);

        # Miscellaneous non-required values
        $house->setRoomCount($this->getOptionalInt($housedata, 'rooms'));
        $house->setArea($this->getOptionalInt($housedata, 'surface_area_m2'));

        # Assign default "Pending" status.
        $house->setStatus(HouseStatus::PENDING);

        return $house;
    }
    
    /**
     * Load houses from the input JSON file into the database for later use by the dashboard UI.
     * No parameters, the file path for the JSON file is in services.yaml.
     * @return bool If loading the houses succeeded or not. (the input file exists or not)
     */
    public function loadHouses(): bool {
        # Load JSON data
        $houses = $this->getHouseData($this->filePath);
        # make sure its valid
        $this->validateHouseData($houses);
        
        # Process each house
        for ($i = 0; $i < count($houses); $i++) {
            # check if this house already exists by attempting to retrieve it from db.
            $externalId = $houses[$i]['listing_id'];
            if ($this->houseExists($externalId)) {
                continue; # skip this one.
            }

            # Create house object from JSON data.
            $house = $this->mapToHouse($houses[$i]);

            # Grade the house.
            $this->houseGrader->gradeHouse($house);

            # save this house
            $this->em->persist($house);
        }
        # actually write them to the database.
        $this->em->flush();

        # return success
        return true;
    }

    
    /**
     * This function loads the houses from the database and
     * filters them based on the query.
     * @return array The houses that match the query.
     **/
    public function getHouses(?HouseStatus $statusQuery): array {
        $repo = $this->em->getRepository(House::class);

        # If there is no query, just get all of them.
        if ($statusQuery === null) {
            $houses = $repo->findAll();

            # Since there is no query, this is all houses.
            # If there are no houses, lets try parsing the input data
            # to fill the database.
            if (count($houses) === 0) {
                # Load houses into db if there are none in the db.
                if ($this->loadHouses()) {
                    # Get houses again
                    $houses = $repo->findAll();
                } # else there are no houses, just return the nothing we found earlier.
            }

            return $houses;
        } else {
            # Find house by query
            return $repo->findBy([
                'status' => $statusQuery,
            ]);
        }
    }
}
