<?php

declare(strict_types=1);

namespace App\Packages\Core;

use Symfony\Component\HttpFoundation\Request;

class Paginator
{
    protected Request $request;
    protected int $total_items;
    protected int $last_page;
    protected int $current_page;
    protected array $data = [];
    protected ?string $first_page_url = null;
    protected ?string $last_page_url = null;
    protected ?string $next_page_url = null;
    public static string $query_page_param = 'page';
    protected int $per_page;
    protected string $path;

    public function __construct(Request $request, array $records, string $base_url, int $total_items, int $per_page)
    {
        $uri = $request->getUri();
        $uri_without_page_query = remove_query_param($uri, self::$query_page_param);

        $current_page = (int) $request->query->get(self::$query_page_param, 1);
        $per_page = (int) $request->query->get('per_page', $per_page);
        $last_page = (int) ceil($total_items / $per_page);
        $next_page = $current_page + 1 > $last_page ? $last_page : $current_page + 1;
        $page_uri = $uri_without_page_query.'&'.self::$query_page_param;

        $this->path = $base_url;
        $this->current_page = $current_page;
        $this->per_page = $per_page;
        $this->data = $records;
        $this->last_page = $last_page;
        $this->total_items = $total_items;
        $this->first_page_url = $page_uri.'=1';
        $this->last_page_url = $page_uri.'='.$last_page;
        $this->next_page_url = $page_uri.'='.$next_page;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'data' => $this->data,
            'last_page' => $this->last_page,
            'total_items' => $this->total_items,
            'current_page' => $this->current_page,
            'per_page' => $this->per_page,
            'last_page_url' => $this->last_page_url,
            'first_page_url' => $this->first_page_url,
            'next_page_url' => $this->next_page_url,
        ];
    }
}
