<?php

namespace App\Services;

use App\Models\DailyRecord;
use App\Contracts\BaseInterface;

class DailyRecordService
{

  public function __construct(protected BaseInterface $repository)
  {
  }

  public function store(array $data): DailyRecord
  { 
    $daily_record = $this->repository->store('DailyRecord', $data);
    return $daily_record;
  }

  public function update(array $data, int $id)
  {
    $this->repository->update('DailyRecord', $data, $id);
  }

  public function delete(int $id)
  {
    $this->repository->delete('DailyRecord', $id);
  }
}
