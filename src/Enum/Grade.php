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

    /**
     * This function returns the proper css class to use depending on
     * the enum value. Twig calls this as [grade name].cssClass
     * @return string css class name as a string
     */
    public function getCssClass(): string {
        return match($this) {
            self::GOOD => 'gradeGoodColor',
            self::WARNING => 'gradeWarnColor',
            self::REJECTED => 'gradeBadColor',
        };
    }
}
