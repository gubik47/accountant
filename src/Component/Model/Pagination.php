<?php

namespace App\Component\Model;

class Pagination extends Base
{
    public function __construct(int $limit, int $page, int $totalItemCount)
    {
        $limit = $limit ?: PHP_INT_MAX;

        $this->data = [
            "items_per_page" => $limit,
            "current_page" => $page,
            "number_of_pages" => intval(ceil($totalItemCount / $limit))
        ];
    }
}