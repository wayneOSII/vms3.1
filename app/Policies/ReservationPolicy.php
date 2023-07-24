<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
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
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->hasRole(['admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($this->isWithinCustomDateRange() && $user->hasRole(['user'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        // return $user->hasRole(['admin','user']) && $this->isWithinLastTwoWeeks($reservation->created_at);
        // 如果是管理員，允許隨時修改
        if ($user->hasRole('admin')) {
            return true;
        }

        // 如果是一般使用者，須確認預約日期在近兩個禮拜內
        return $this->isWithinLastTwoWeeks($reservation->created_at);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        // return $user->hasRole(['admin']);
        // 如果是管理員，允許隨時修改
        if ($user->hasRole('admin')) {
            return true;
        }

        // 如果是一般使用者，須確認預約日期在近兩個禮拜內
        return $this->isWithinLastTwoWeeks($reservation->created_at);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        // return $user->hasRole(['admin']);
    }

    /**
     * Check if a given date is within the last two weeks.
     */
    private function isWithinLastTwoWeeks($date): bool
    {
        return now()->subWeeks(2)->lte($date);
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
