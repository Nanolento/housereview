<?php
namespace App\Enum;

enum Grade: string {
    case REJECTED = 'Rejected';
    case WARNING = 'Warning';
    case GOOD = 'Good';
}
