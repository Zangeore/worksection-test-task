<?php

namespace App\Http;

use App\Services\TasksService;
use Core\Http\Render\RendererInterface;
use Core\Http\Request;
use Core\Http\Response;

class TodoController
{
    public function index(RendererInterface $renderer, TasksService $service): Response
    {
        return Response::html($renderer->render('todo/index', ['tasks' => $service->index()]));
    }

    public function store(Request $request, TasksService $service): Response
    {
        $service->store($request->body());
        return Response::json([
            'tasks' => $service->index()
        ]);
    }

    public function update(Request $request, TasksService $service): Response
    {
        $service->update($request->all());
        return Response::json([
            'tasks' => $service->index()
        ]);
    }

    public function destroy(Request $request, TasksService $service): Response
    {
        $service->destroy($request->route('id'));
        return Response::json([
            'tasks' => $service->index()
        ]);
    }
}
