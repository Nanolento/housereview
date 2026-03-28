<?php

namespace App\Enum;

enum HouseStatus: string {
    case PENDING = "Pending";
    case APPROVED = "Approved";
    case REJECTED = "Rejected";
}
