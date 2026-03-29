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

    # Note: the below values are defined as string to make
    # it possible to write "(not specified)" if the values are null
    public string $roomCountString = "2";
    public string $areaString = "50 m2";
}
