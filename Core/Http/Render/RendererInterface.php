<?php

namespace Core\Http\Render;

interface RendererInterface
{
    public function render(string $view, array $params = [], $layout = 'default'): string;
}