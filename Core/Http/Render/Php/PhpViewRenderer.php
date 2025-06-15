<?php

namespace Core\Http\Render\Php;

use Core\Http\Render\RendererInterface;

class PhpViewRenderer implements RendererInterface
{

    /**
     * @var string
     */
    protected $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function render(string $view, array $params = [], $layout = 'default'): string
    {
       $context = new PhpViewContext($this, $this->basePath);
        return $context->renderPartial("layouts/$layout", [
            'content' => $context->renderPartial($view, $params),
        ]);

    }
}