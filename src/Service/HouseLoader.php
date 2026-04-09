<?php
namespace App\Service;

use App\Entity\House;

use App\Enum\Grade;
use App\Enum\HouseStatus;

use Doctrine\ORM\EntityManagerInterface;

/*
The HouseLoader class takes houses from the JSON data and puts them in the SQL
database. If run when the database is not empty, it will add the houses that were
not yet in the database to the database. It also does the grading of the houses.
*/

class HouseLoader {

    public function __construct(private string $filePath, private EntityManagerInterface $em) {}

    /**
     * Load houses from the input JSON file into the database for later use by the dashboard UI.
     * No parameters, the file path for the JSON file is in services.yaml.
     * @return bool If loading the houses succeeded or not. (the input file exists or not)
     */
    public function loadHouses(): bool {
        # Load JSON data
        if (!file_exists($this->filePath)) {
            return false;
        }
        $housefile = file_get_contents($this->filePath);
        $houses = json_decode($housefile, true); # associative because arrays are easier

        # Process each house
        for ($i = 0; $i < count($houses); $i++) {
            if (!isset($houses[$i]['listing_id'])) {
                # This house does not have an ID we can check and is therefore invalid.
                continue;
            }
            # check if this house already exists by attempting to retrieve it from db.
            $externalId = $houses[$i]['listing_id'];
            $existingHouse = $this->em->getRepository(House::class)->findOneBy([
                'externalId' => $externalId,
            ]);

            if ($existingHouse) {
                continue; # skip adding this one as it already exists.
            }

            # Create house object and set values
            $house = new House();
            $house->setExternalId($externalId);
            # Make sure title is string, else make it null.
            if (isset($houses[$i]['headline']) && is_string($houses[$i]['headline'])) {
                $house->setTitle($houses[$i]['headline']);
            } else {
                $house->setTitle(null);
            }
            # Monthly rent: should be int, make it null if not.
            if (isset($houses[$i]['monthly_rent']) && is_int($houses[$i]['monthly_rent'])) {
                $house->setMonthlyRent($houses[$i]['monthly_rent']);
            } else {
                # Invalid type should also just be null
                $house->setMonthlyRent(null);
            }
            # Energy label, should be a short string.
            if (isset($houses[$i]['energy_class']) && is_string($houses[$i]['energy_class'])) {
                $house->setEnergyLabel($houses[$i]['energy_class']);
            } else {
                $house->setEnergyLabel(null);
            }
            # Set city.
            $house->setCity($houses[$i]['location_city']);

            # Miscellaneous non-required values
            # Room count
            if (isset($houses[$i]['rooms']) && is_integer($houses[$i]['rooms'])) {
                $house->setRoomCount($houses[$i]['rooms']);
            } else {
                $house->setRoomCount(null);
            }
            # Surface Area (m^2)
            if (isset($houses[$i]['surface_area_m2']) && is_integer($houses[$i]['surface_area_m2'])) {
                $house->setArea($houses[$i]['surface_area_m2']);
            } else {
                $house->setArea(null);
            }

            # Grade the house.
            $this->gradeHouse($house);

            # Assign default "Pending" status.
            $house->setStatus(HouseStatus::PENDING);

            # save this house
            $this->em->persist($house);
        }
        # actually write them to the database.
        $this->em->flush();

        # return success
        return true;
    }

    /**
     * Grades houses based on their title, monthly rent and energy label.
     * This sets the 'titleGrade', 'rentGrade', 'energyGrade' and 'overallGrade'
     * in the House object.
     * @param House house The house to grade and set grades for.
     */
    private function gradeHouse(House $house): void {
        $overallGrade = Grade::GOOD;
        # In the below grading, according to the rules, this grade will drop
        # depending on the grades given to individual things.
        
        # Grading title
        # Not a string, null or empty -> REJECTED
        # Less than 10 chars -> WARNING
        # else -> GOOD
        $house_title = $house->getTitle();
        if (!is_string($house_title) || strlen($house_title) === 0) {
            $house->setTitleGrade(Grade::REJECTED);
            $overallGrade = Grade::REJECTED;
        } elseif (strlen($house_title) < 10) {
            $house->setTitleGrade(Grade::WARNING);
            $overallGrade = Grade::WARNING;
        } else {
            $house->setTitleGrade(Grade::GOOD);
        }

        # Grading rent
        # If rent is non-numeric or less than or equal to 0 -> REJECTED
        # else -> GOOD
        $house_rent = $house->getMonthlyRent();
        if (!is_int($house_rent) || $house_rent <= 0) {
            $house->setRentGrade(Grade::REJECTED);
            $overallGrade = Grade::REJECTED;
        } else {
            $house->setRentGrade(Grade::GOOD);
        }

        # Grading Energy Label
        $valid_energy_labels = [
            'A++++', 'A+++', 'A++',
            'A+', 'A', 'B',
            'C', 'D', 'E',
            'F', 'G'
        ];
        $house_energy_label = $house->getEnergyLabel();
        # Empty or missing/null -> WARNING, Invalid value (eg. Z) -> REJECTED, in valid_energy_labels -> GOOD
        if (!is_string($house_energy_label) || strlen($house_energy_label) === 0) {
            $house->setEnergyGrade(Grade::WARNING);
            if ($overallGrade !== Grade::REJECTED) {
                $overallGrade = Grade::WARNING;
            }
        } elseif (!in_array($house_energy_label, $valid_energy_labels, true)) {
            $house->setEnergyGrade(Grade::REJECTED);
            $overallGrade = Grade::REJECTED;
        } else {
            $house->setEnergyGrade(Grade::GOOD);
        }

        # Overall grading
        # REJECTED "Attention Required": >=1 grades are REJECTED.
        # WARNING "Check Needed": None REJECTED, but >=1 are WARNING.
        # GOOD "Ready for Review": Everything is GOOD.
        # Processed above.
        $house->setOverallGrade($overallGrade);
    }
}
