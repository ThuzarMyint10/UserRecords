<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use App\Traits\GenderCountTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchAndStoreUserRecordsCommand extends Command
{
    /* Fetching the data from the Api
       Inserting the data into the Users Table & Redis */

    use GenderCountTrait;
    public function __construct(protected UserService $service)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:users';

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
            // Call the API
            $response = Http::get('https://randomuser.me/api/?results=20');

            if ($response->successful()) {
                $fetchingUsers = $response->json()['results'];
                $finalUserData = [];

                foreach ($fetchingUsers as $fetchingUserData) {
                    // Check if user exists by uuid
                    $uuid = $fetchingUserData['login']['uuid'];
                    $data = [
                        'uuid' => $uuid,
                        'gender' => $fetchingUserData['gender'],
                        'name' => json_encode($fetchingUserData['name']),
                        'location' => json_encode($fetchingUserData['location']),
                        'age' => $fetchingUserData['dob']['age'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $user = User::where('uuid', $uuid)->first();
                    $user ? $this->service->update($data, $user->id) : array_push($finalUserData, $data);
                }
                $this->service->store($finalUserData);
                $this->createRedisInfo($fetchingUsers);
                $this->info('Users fetched and stored successfully.');
            }
        } catch (\Exception $e) {
            $this->error('Error fetching users: ' . $e->getMessage());
        }
    }


    public function createRedisInfo($fetchingUsers)
    {
        // Group and count users by gender
        $genderCounts = collect($fetchingUsers)->groupBy('gender')->map->count()->toArray();

        // Format gender counts for Redis
        $formattedGenderCounts = [
            'female_count' => $genderCounts['female'] ?? 0,
            'male_count' => $genderCounts['male'] ?? 0,
        ];
        // Increment gender counts in Redis
        foreach ($formattedGenderCounts as $key => $genderCount) {
            $this->incrementGenderCountInRedis($key, $genderCount);
        }
    }
}
