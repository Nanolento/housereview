<?php

namespace App\Entity;

use App\Enum\Grade;
use App\Enum\HouseStatus;

use App\Repository\HouseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $externalId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $monthlyRent = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $energyLabel = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(nullable: true, enumType: Grade::class)]
    private ?Grade $titleGrade = null;

    #[ORM\Column(nullable: true, enumType: Grade::class)]
    private ?Grade $rentGrade = null;

    #[ORM\Column(nullable: true, enumType: Grade::class)]
    private ?Grade $energyGrade = null;

    #[ORM\Column(nullable: true, enumType: Grade::class)]
    private ?Grade $overallGrade = null;

    #[ORM\Column(enumType: HouseStatus::class)]
    private ?HouseStatus $status = HouseStatus::PENDING;

    #[ORM\Column(nullable: true)]
    private ?int $area = null;

    #[ORM\Column(nullable: true)]
    private ?int $roomCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getMonthlyRent(): ?int
    {
        return $this->monthlyRent;
    }

    public function setMonthlyRent(?int $monthlyRent): static
    {
        $this->monthlyRent = $monthlyRent;

        return $this;
    }

    public function getEnergyLabel(): ?string
    {
        return $this->energyLabel;
    }

    public function setEnergyLabel(?string $energyLabel): static
    {
        $this->energyLabel = $energyLabel;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getTitleGrade(): ?Grade
    {
        return $this->titleGrade;
    }

    public function setTitleGrade(?Grade $titleGrade): static
    {
        $this->titleGrade = $titleGrade;

        return $this;
    }

    public function getRentGrade(): ?Grade
    {
        return $this->rentGrade;
    }

    public function setRentGrade(?Grade $rentGrade): static
    {
        $this->rentGrade = $rentGrade;

        return $this;
    }

    public function getEnergyGrade(): ?Grade
    {
        return $this->energyGrade;
    }

    public function setEnergyGrade(?Grade $energyGrade): static
    {
        $this->energyGrade = $energyGrade;

        return $this;
    }

    public function getOverallGrade(): ?Grade
    {
        return $this->overallGrade;
    }

    public function setOverallGrade(?Grade $overallGrade): static
    {
        $this->overallGrade = $overallGrade;

        return $this;
    }

    public function getStatus(): ?HouseStatus
    {
        return $this->status;
    }

    public function setStatus(HouseStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(?int $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getRoomCount(): ?int
    {
        return $this->roomCount;
    }

    public function setRoomCount(?int $roomCount): static
    {
        $this->roomCount = $roomCount;

        return $this;
    }
}
