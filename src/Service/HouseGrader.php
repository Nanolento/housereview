<?php
namespace App\Service;

use App\Entity\House;

use App\Enum\Grade;

use Doctrine\ORM\EntityManagerInterface;

/*
The HouseGrader class grades houses according to our rules.
*/

class HouseGrader {

    /**
     * Grades the title of a house according to the rules.
     * @param ?string The house title
     * @return Grade The grade the title received.
     */
    private function gradeTitle(?string $house_title): Grade {
        # Rules:
        # title missing or empty string -> REJECTED
        # title less than 10 chars -> WARNING
        # else GOOD.
        if (!is_string($house_title) || strlen($house_title) === 0) {
            return Grade::REJECTED;
        } elseif (strlen($house_title) < 10) {
            return Grade::WARNING;
        } else {
            return Grade::GOOD;
        }
    }

    /**
     * Grades the monthly rent of a house according to the rules.
     * @param ?string The house's monthly rent
     * @return Grade The grade the monthly rent received.
     */
    private function gradeMonthlyRent(?int $house_rent): Grade {
        # Rules:
        # Rent missing, or <= 0 -> REJECTED.
        # else GOOD.
        if (!is_int($house_rent) || $house_rent <= 0) {
            return Grade::REJECTED;
        } else {
            return Grade::GOOD;
        }
    }

    /**
     * Grades the energy label of a house according to the rules.
     * @param ?string The house's energy label
     * @return Grade The grade the energy label received.
     */
    private function gradeEnergyLabel(?string $house_energy_label): Grade {
        # Rules:
        # Empty, missing/null -> WARNING,
        # Invalid value (not in valid energy labels) -> REJECTED
        # Valid value -> GOOD
        $valid_energy_labels = [
            'A++++', 'A+++', 'A++',
            'A+', 'A', 'B',
            'C', 'D', 'E',
            'F', 'G'
        ];
        # Empty or missing/null -> WARNING, Invalid value (eg. Z) -> REJECTED, in valid_energy_labels -> GOOD
        if (!is_string($house_energy_label) || strlen($house_energy_label) === 0) {
            return Grade::WARNING;
        } elseif (!in_array($house_energy_label, $valid_energy_labels, true)) {
            return Grade::REJECTED;
        } else {
            return Grade::GOOD;
        }

    }

    /**
     * Gets the overall grade of a house depending on its sub-grades.
     * @param House The house object
     * @return Grade The overall grade the house received.
     */
    private function gradeOverall(House $house): Grade {
        # Overall grading rules:
        # REJECTED "Attention Required": >=1 grades are REJECTED.
        # WARNING "Check Needed": None REJECTED, but >=1 are WARNING.
        # GOOD "Ready for Review": Everything is GOOD.
        $grades = [
            $house->getTitleGrade(),
            $house->getRentGrade(),
            $house->getEnergyGrade(),
        ];
        # If any REJECTED grades, REJECTED.
        if (in_array(Grade::REJECTED, $grades)) {
            return Grade::REJECTED;
        }
        # If any WARNING grades, WARNING
        if (in_array(Grade::WARNING, $grades)) {
            return Grade::WARNING;
        }
        # else all is GOOD.
        return Grade::GOOD;
    }
    
    /**
     * Grades houses based on their title, monthly rent and energy label.
     * This sets the 'titleGrade', 'rentGrade', 'energyGrade' and 'overallGrade'
     * in the House object.
     * @param House house The house to grade and set grades for.
     */
    public function gradeHouse(House $house): void {
        # In the below grading, according to the rules, this grade will drop
        # depending on the grades given to individual things.
        
        # Grading
        $house_title = $house->getTitle();
        $house->setTitleGrade($this->gradeTitle($house_title));

        $house_rent = $house->getMonthlyRent();
        $house->setRentGrade($this->gradeMonthlyRent($house_rent));

        $house_energy_label = $house->getEnergyLabel();
        $house->setEnergyGrade($this->gradeEnergyLabel($house_energy_label));

        # Overall grading
        $house->setOverallGrade($this->gradeOverall($house));
    }
}
