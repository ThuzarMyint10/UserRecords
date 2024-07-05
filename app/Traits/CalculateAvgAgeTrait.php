<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Carbon;

/* This trait provides methods to calculate the average age of users
 by gender for the current day. */
trait CalculateAvgAgeTrait
{
    protected $startOfDay;
    protected $endOfDay;

    // Initialize the start and end of the day.
    public function initializeDate()
    {
        $this->startOfDay = Carbon::now()->startOfDay();
        $this->endOfDay = Carbon::now()->endOfDay();
    }

    // calculate the average age
    public function calculateAvgAge()
    {
        return User::whereBetween('created_at', [$this->startOfDay, $this->endOfDay])
                ->selectRaw('gender, AVG(age) as avg_age')
                ->groupBy('gender')
                ->pluck('avg_age', 'gender')->toArray();    
    }
}
