<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return $user->hasRole(['admin','user']);
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($this->isWithinCustomDateRange() && $user->hasRole(['user'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Area $area): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Area $area): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Area $area): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Area $area): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Area $area): bool
    {
        //
    }

    /**
     * Check if the current date is within custom date ranges for each year.
     */
    private function isWithinCustomDateRange(): bool
    {
        // 取得當前日期
        $currentDate = Carbon::now();

        // 第一個時段：每年從7月15日到7月31日
        $startDate1 = Carbon::create($currentDate->year, 7, 15);
        $endDate1 = Carbon::create($currentDate->year, 7, 31);

        // 第二個時段：每年從8月1日到8月15日
        $startDate2 = Carbon::create($currentDate->year, 8, 1);
        $endDate2 = Carbon::create($currentDate->year, 8, 15);

        // 確認是否在第一個時段或第二個時段內
        $isWithinRange1 = $currentDate->between($startDate1, $endDate1, true);
        $isWithinRange2 = $currentDate->between($startDate2, $endDate2, true);

        return $isWithinRange1 || $isWithinRange2;
    }

}
