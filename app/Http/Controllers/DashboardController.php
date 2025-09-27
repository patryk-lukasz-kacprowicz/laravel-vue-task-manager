<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response {
        return Inertia::render('Dashboard', [
            'tasksToday' => $this->getTodayTasks(),
        ]);
    }

    /**
     * @return AnonymousResourceCollection
     */
    private function getTodayTasks(): AnonymousResourceCollection {
        return TaskResource::collection(
            Task::query()
                ->where('user_id', auth()->id())
                ->where('is_completed', false)
                ->whereDate('deadline_date', now()->today())
                ->latest()
                ->get()
        );
    }
}
