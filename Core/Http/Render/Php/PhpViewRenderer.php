<?php

namespace Core\Http\Render\Php;

use Core\Http\Render\AssetManagerInterface;
use Core\Http\Render\RendererInterface;

class PhpViewRenderer implements RendererInterface
{

    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var AssetManager
     */
    protected $assetManager;

    public function __construct(string $basePath, AssetManagerInterface $assetManager)
    {
        $this->basePath = rtrim($basePath, '/');
        $this->assetManager = $assetManager;
    }

    public function render(string $view, array $params = [], $layout = 'default'): string
    {
       $context = new PhpViewContext($this, $this->basePath, $this->assetManager);
        return $context->renderPartial("layouts/$layout", [
            'content' => $context->renderPartial($view, $params),
        ]);

    }
}