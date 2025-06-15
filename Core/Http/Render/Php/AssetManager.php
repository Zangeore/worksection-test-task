<?php

namespace Core\Http\Render\Php;

use Core\Http\Render\AssetManagerInterface;

class AssetManager implements AssetManagerInterface
{
    /**
     * @var array
     */
    protected $scripts = [];
    /**
     * @var array
     */
    protected $styles = [];

    public function addScript(string $src, bool $defer = false): void
    {
        $this->scripts[] = ['src' => $src, 'defer' => $defer];
    }

    public function addStyle(string $href): void
    {
        $this->styles[] = $href;
    }

    public function renderScripts(): string
    {
        return implode("\n", array_map(function ($script) {
            $defer = $script['defer'] ? ' defer' : '';
            return "<script src=\"{$script['src']}\"$defer></script>";
        }, $this->scripts));
    }

    public function renderStyles(): string
    {
        return implode("\n", array_map(function ($href) {
            return "<link rel=\"stylesheet\" href=\"$href\">";
        }, $this->styles));
    }
}