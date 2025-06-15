<?php

namespace Core\Http;

class Response
{
    /**
     * @var string
     */
    protected $content;
    /**
     * @var int
     */
    protected $status;
    /**
     * @var array
     */
    protected $headers;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }

    public static function json(array $data, int $status = 200): Response
    {
        return new static(json_encode($data, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT), $status, [
            'Content-Type' => 'application/json',
        ]);
    }

    public static function html(string $html, int $status = 200): Response
    {
        return new static($html, $status, ['Content-Type' => 'text/html']);
    }

    public static function text(string $text, int $status = 200): Response
    {
        return new static($text, $status, ['Content-Type' => 'text/plain']);
    }

    public static function redirect(string $url, int $status = 302): Response
    {
        return new static('', $status, ['Location' => $url]);
    }
}