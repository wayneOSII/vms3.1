<?php

namespace App\Policies;

use App\Models\CurrentAcademicYearAndSemester;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CurrentAcademicYearAndSemesterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CurrentAcademicYearAndSemester $currentAcademicYearAndSemester): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CurrentAcademicYearAndSemester $currentAcademicYearAndSemester): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CurrentAcademicYearAndSemester $currentAcademicYearAndSemester): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CurrentAcademicYearAndSemester $currentAcademicYearAndSemester): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CurrentAcademicYearAndSemester $currentAcademicYearAndSemester): bool
    {
        //
    }
}
