<?php

namespace Core\Http\Render\Php;

class PhpViewContext
{
    protected $renderer;
    protected $basePath;
    /**
     * @var AssetManager
     */
    protected $assetManager;

    public function __construct(
        PhpViewRenderer $renderer,
        string $basePath,
        AssetManager $assetManager
    ) {
        $this->basePath = $basePath;
        $this->renderer = $renderer;
        $this->assetManager = $assetManager;
    }

    public function renderPartial(string $view, array $params = []): string
    {
        $file = $this->basePath . '/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("Partial not found: $file");
        }

        return $this->renderFile($file, $params);
    }

    public function renderFile(string $file, array $params = []): string
    {
        ob_start();
        extract($params, EXTR_SKIP);
        include $file;
        return ob_get_clean();
    }

}