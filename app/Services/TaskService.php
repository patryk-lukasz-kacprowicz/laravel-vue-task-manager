<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Throwable;

class TaskService
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function store(array $data): bool {
        try {
            $data['completion_date'] = $data['is_completed'] ? now() : null;
            $data['deadline_date'] ??= now()->endOfDay();

            Task::query()->create($data);

            return true;
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }

    /**
     * @param array $data
     * @param Task $task
     *
     * @return bool
     */
    public function update(array $data, Task $task): bool {
        try {
            $data['completion_date'] = $data['is_completed'] ? now() : null;
            $data['deadline_date'] ??= now()->endOfDay();

            $task->update($data);

            return true;
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }

    /**
     * @param Task $task
     *
     * @return bool
     */
    public function destroy(Task $task): bool {
        try {
            $task->delete();

            return true;
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }
}
