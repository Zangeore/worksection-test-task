<?php

namespace App\Http;

use Core\Http\Render\RendererInterface;
use Core\Http\Response;

class TodoController
{
    public function index(RendererInterface $renderer): Response
    {
        return Response::html($renderer->render('todo/index', ['title' => 'var injected']));
    }
}