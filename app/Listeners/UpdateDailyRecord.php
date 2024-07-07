<?php

namespace App\Listeners;

use App\Models\DailyRecord;
use App\Traits\GenderCountTrait;
use App\Events\UserRecordDeleted;
use App\Traits\CalculateAvgAgeTrait;

class UpdateDailyRecord
{
    /* If user delete the record, need to update dailyrecord table & redis */
    use CalculateAvgAgeTrait, GenderCountTrait;
   

    /**
     * Handle the event.
     */
    public function handle(UserRecordDeleted $event): void
    {
        $this->initializeDate($event->date);
        $avgAge = $this->calculateAvgAge();
        $query = DailyRecord::where('date', $event->date);
        $this->updateDailyRecord($event->gender, $query, $avgAge);
    }

    public function updateDailyRecord($gender, $query, $avgAge)
    {
        $gender_count = '';
        $gender_avg_age = '';
        $gender_avg_age_key = '';

        if ($gender !== 'female') {
            // Decrement male count and update male_avg_age
            $gender_count = 'male_count';
            $gender_avg_age =  $avgAge['male'];
            $gender_avg_age_key = 'male_avg_age';
        } else {
            // Decrement female count and update female_avg_age
            $gender_count = 'female_count';
            $gender_avg_age = $avgAge['female'];
            $gender_avg_age_key = 'female_avg_age';
        }

        $query->decrement($gender_count);
        $query->update([$gender_avg_age_key => $gender_avg_age]);
        $this->decrementGenderCountInRedis($gender_count);
    }
}
