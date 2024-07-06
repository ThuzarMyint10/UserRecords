<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DailyRecordService;
use App\Traits\CalculateAvgAgeTrait;
use Illuminate\Support\Facades\Redis;

class ProcessDailyRecordsCommand extends Command
{
    /* Fetches gender counts from Redis, calculates averages,
       and stores the daily records into the DailyRecord table. */

    use CalculateAvgAgeTrait;
    public function __construct(protected DailyRecordService $service)
    {
        parent::__construct();
        $this->initializeDate(now());
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Retrieve male and female counts from Redis or default to 0
            $maleCount = Redis::get('male_count') ?? 0;
            $femaleCount = Redis::get('female_count') ?? 0;

            // Calculate average ages for males and females
            $avgAge = $this->calculateAvgAge(now());

            // Prepare data to store in DailyRecord table
            $data = [
                'date' => now(),
                'male_count' => $maleCount,
                'female_count' => $femaleCount,
                'male_avg_age' => $avgAge['male'],
                'female_avg_age' => $avgAge['female'],
            ];

            // Store data using DailyRecordService
            $this->service->store($data);

            // Clear male and female count keys in Redis
            Redis::del('male_count');
            Redis::del('female_count');

            // Output success message to console
            $this->info('Daily records processed successfully!');
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            $this->error('Error processing daily records: ' . $e->getMessage());
        }
    }
}
