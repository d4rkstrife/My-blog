<?php

declare(strict_types=1);

namespace App\Service;

final class Pagination
{

    private string $config;
    private string $order;


    public function __construct(string $config, string $order)
    {
        $this->config = $config;
        $this->order = $order;
    }
    public function render(int $itemNumber, int $page): array
    {
        $nbrPage = $itemNumber / (int)$this->config;
        $offset = ($page * (int)$this->config) - (int)$this->config;
        $infos = array(
            'limit' => (int) $this->config,
            'offset' => $offset,
            'order' => $this->order,
            'nbrPages' => $nbrPage
        );
        return $infos;
    }
}
