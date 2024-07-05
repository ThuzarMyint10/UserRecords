<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

//This trait provides methods to increment and decrement gender counts in Redis.
trait GenderCountTrait
{
    /**
     * Increment the gender count in Redis by a specified amount.
     *
     * @param string $redisKey The Redis key to increment.
     * @param int $incrementBy The amount to increment by.
     * @return void
     */
    public function incrementGenderCountInRedis($redisKey, $incrementBy)
    {
        Redis::incrby($redisKey, $incrementBy);
    }

    /**
     * Decrement the gender count in Redis by one.
     *
     * @param string $redisKey The Redis key to decrement.
     * @return void
     */
    public function decrementGenderCountInRedis($redisKey)
    {
        if (Redis::get($redisKey)) {
            Redis::decrby($redisKey, 1);
        }
    }
}
