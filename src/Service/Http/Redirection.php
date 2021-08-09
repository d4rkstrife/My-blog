<?php

declare(strict_types=1);

namespace App\Service\Http;

use App\View\View;
use App\Service\Http\Response;

final class Redirection
{
    private View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }
    public function send(string $string): Response
    {
        switch ($string) {
            case 'home':
                return new Response('<meta http-equiv="refresh" content="0; URL=index.php?action=home" />');
                break;
            case 'login':
                return new Response('<meta http-equiv="refresh" content="0; URL=index.php?action=login" />');
                break;
        }
    }
}
