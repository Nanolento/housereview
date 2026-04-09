<?php

namespace App\Enum;

enum HouseStatus: string {
    case PENDING = "Pending";
    case APPROVED = "Approved";
    case REJECTED = "Rejected";

    /**
     * This function returns the proper css class to use depending on
     * the enum value. Twig calls this as house.status.cssClass
     * @return string css class name as a string
     */
    public function getCssClass(): string {
        return match($this) {
            self::APPROVED => 'statusApprovedColor',
            self::REJECTED => 'statusRejectedColor',
            self::PENDING => 'statusPendingColor',
        };
    }
}
