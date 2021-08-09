<?php

declare(strict_types=1);

namespace App\Service;

final class Pagination
{

    private string $config;


    public function __construct(string $config)
    {
        $this->config = $config;
    }
    public function render(int $itemNumber, int $page): array
    {
        $nbrPage = $itemNumber / (int)$this->config;
        $offset = ($page * (int)$this->config) - (int)$this->config;
        $infos = array(
            'limit' => (int) $this->config,
            'offset' => $offset,
            'nbrPages' => $nbrPage
        );
        return $infos;
    }
}
