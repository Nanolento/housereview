<?php
namespace App\Service;

use App\Entity\House;
use Doctrine\ORM\EntityManagerInterface;

/*
The HouseLoader class takes houses from the JSON data and puts them in the SQL
database. If run when the database is not empty, it will add the houses that were
not yet in the database to the database.
*/

class HouseLoader {

    public function __construct(private string $filePath, private EntityManagerInterface $em) {}

    /**
     * Load houses from the input JSON file into the database for later use by the dashboard UI.
     * No parameters, the file path for the JSON file is in services.yaml.
     */
    public function loadHouses() {
        # Load JSON data
        $housefile = file_get_contents($this->filePath);
        $houses = json_decode($housefile, true); # associative because arrays are easier

        # Process each house
        for ($i = 0; $i < count($houses); $i++) {
            # Create house object and set values
            $house = new House();
            $house->setExternalId($houses[$i]['listing_id']);
            $house->setTitle($houses[$i]['headline']);
            $house->setMonthlyRent($houses[$i]['monthly_rent']);
            $house->setEnergyLabel($houses[$i]['energy_class']);
            $house->setCity($houses[$i]['location_city']);

            # save this house
            $this->em->persist($house);
        }
        # actually write them to the database.
        $this->em->flush();
    }
}
