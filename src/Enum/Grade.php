<?php
namespace App\Enum;

enum Grade: string {
    case REJECTED = 'Rejected';
    case WARNING = 'Warning';
    case GOOD = 'Good';

    /**
     * This function returns the Grade as a Total score label rather than
     * a basic grade. They essentially have the same meaning but like this
     * they're worded a bit differently so the overall grade can be represented
     * properly in the UI.
     * @return string The total score label corresponding to the grade.
     **/
    public function getTotalScoreLabel(): string {
        return match($this) {
            self::GOOD => 'Ready for Review',
            self::WARNING => 'Check Needed',
            self::REJECTED => 'Attention Required',
        };
    }
}
