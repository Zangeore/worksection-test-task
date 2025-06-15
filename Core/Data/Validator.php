<?php

namespace Core\Data;

use Core\Exception\Exceptions\ValidationException;

class Validator
{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $rules;
    /**
     * @var array
     */
    protected $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function passes(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $callbacks) {
            $value = $this->data[$field] ?? null;

            foreach ($callbacks as $callback) {
                $error = $callback($value, $this->data);
                if ($error !== null) {
                    $this->errors[$field][] = $error;
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function validated(): array
    {
        if (!$this->passes()) {
            throw new ValidationException($this->errors());
        }

        return array_intersect_key($this->data, $this->rules);
    }
}
