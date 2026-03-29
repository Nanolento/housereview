<?php
namespace App\Twig\Components;

use App\Enum\Grade;
use App\Enum\HouseStatus;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class HouseListing {
    use DefaultActionTrait;
    
    public string $title = 'House';
    public int $monthlyRent = 0;
    public string $city = 'Amsterdam';
    public string $energyLabel = 'A';

    public Grade $rentGrade = Grade::REJECTED;
    public Grade $titleGrade = Grade::WARNING;
    public Grade $energyGrade = Grade::GOOD;

    public Grade $overallGrade = Grade::REJECTED;

    public HouseStatus $status = HouseStatus::PENDING;

    public int $roomCount = 2;
    public int $area = 50;
}
