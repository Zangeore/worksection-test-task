<?php

namespace Core\Data;
abstract class Model
{
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->load($data);
        }
    }

    public function load(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public static function map(array $data): array
    {
        return array_map(static function ($item) {
            return new static($item);
        }, $data);
    }

    public static function make(array $data = []): Model
    {
        return new static($data);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}