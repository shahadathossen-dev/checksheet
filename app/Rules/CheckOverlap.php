<?php

namespace App\Rules;

use App\Enums\LeaveType;
use App\Models\Leave;
use Illuminate\Contracts\Validation\Rule;

class CheckOverlap implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $type =  request()->input('type');
        $endDate =  request()->input('endDate');
        $startDate = request()->input('startDate');
        $userId = request()->input('userId') ?? auth()->id();

        $leaveOpverlaps = Leave::whereBetween('start_date', [$startDate, $endDate]) // st < dst < et 
            ->orWhereBetween('end_date', [$startDate, $endDate]) // st < det < et
            ->orWhere([['start_date', '<=', $startDate], ['end_date', '>=', $startDate]]) // dst <= st <= det
            ->orWhere([['start_date', '<=', $endDate], ['end_date', '>=', $endDate]]) // dst <= et <= det
            ->when(
                $type == LeaveType::INDIVIDUAL(), 
                fn($query) => $query->where('user_id', $userId),
                fn($query) => $query->where('type', LeaveType::GENERAL()),
            )
            ->exists();

        return $leaveOpverlaps;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Requested date overlaps with existing leaves.';
    }
}
