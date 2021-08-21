<?php

declare(strict_types=1);

namespace App\Service\Http;

final class Response
{
    private string $content;
    private int $status;
    private ?array $headers;

    function __construct(string $content = '', int $status = 200, array $headers = null)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send(): void
    {
        if ($this->headers === null) {
            echo $this->content;
        } elseif ($this->headers !== null) {

            header('Location: index.php?action=' . $this->headers['redirect'], true, $this->status);
            exit;
        }
    }
}
