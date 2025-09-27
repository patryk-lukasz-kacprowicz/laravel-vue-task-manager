<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /** @var TaskService  */
    protected TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response {
        $filters = $request->only(['status', 'priority', 'sort_column', 'sort_direction']);

        $sortColumn = $filters['sort_column'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        $tasks = Task::query()
            ->where('user_id', auth()->id())
            ->filterByStatus($filters['status'] ?? null)
            ->filterByPriority($filters['priority'] ?? null)
            ->applySort($sortColumn, $sortDirection)
            ->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => TaskResource::collection($tasks),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response {
        return Inertia::render('Tasks/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     *
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse {
        $result = $this->taskService->store($request->validated());

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully',
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Task creation failed',
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     *
     * @return Response
     */
    public function show(Task $task): Response {
        $this->authorize('view', $task);

        return Inertia::render('Tasks/Show', [
            'task' => TaskResource::make($task),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     *
     * @return Response
     */
    public function edit(Task $task): Response {
        $this->authorize('view', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => TaskResource::make($task),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskRequest $request
     * @param Task $task
     *
     * @return JsonResponse
     */
    public function update(TaskRequest $request, Task $task): JsonResponse {
        $this->authorize('update', $task);

        $result = $this->taskService->update($request->validated(), $task);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Task update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     *
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse {
        $this->authorize('delete', $task);

        $result = $this->taskService->destroy($task);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Task delete failed',
        ], 500);
    }
}
