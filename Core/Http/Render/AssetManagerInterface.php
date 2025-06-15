<?php

namespace Core\Http\Render;

interface AssetManagerInterface
{
    public function addScript(string $src, bool $defer = false): void;
    public function addStyle(string $href): void;


}