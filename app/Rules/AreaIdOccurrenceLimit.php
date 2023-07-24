<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
// use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule; // Import the Rule interface instead of ValidationRule
use Illuminate\Support\Collection;

class AreaIdOccurrenceLimit implements Rule
{
    /**
     * The area ID to check the occurrence limit for.
     *
     * @var 
     */
    private $areaIds;
    private $semesters;

    /**
     * Create a new rule instance.
     *
     * @param int $areaId The area ID to check the occurrence limit for.
     */
    public function __construct(Collection $areaIds, Collection $semesters)
    {
        $this->areaIds = $areaIds;
        $this->semesters = $semesters;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->semesters as $semester) {
            foreach ($this->areaIds as $areaId) {
                $limit = 3; // Set the limit to 3 occurrences of area_id = 1

                if ($areaId == $value) {
                    $count = DB::table('reservations')
                        ->where('area_id', $areaId)
                        ->where('semester', $semester)
                        ->count();

                    if ($count >= $limit) {
                        return false; // Validation fails
                    }
                }
            }
        }

        return true; // Validation passes
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute can only appear 3 times in a semester.";
    }
}
