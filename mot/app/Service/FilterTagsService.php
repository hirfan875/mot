<?php

namespace App\Service;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterTagsService
{
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Create a new filter tags instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->query = Tag::query();
    }

    /**
     * set active filter
     *
     * @return FilterTagsService
     */
    public function active(): FilterTagsService
    {
        $this->query->active();
        return $this;
    }

    /**
     * include only admin tags
     *
     * @return FilterTagsService
     */
    public function forAdmin(): FilterTagsService
    {
        $this->query->whereIsAdmin(1);
        return $this;
    }

    /**
     * include only seller tags
     *
     * @return FilterTagsService
     */
    public function forSeller(): FilterTagsService
    {
        $this->query->whereNull('is_admin');
        return $this;
    }

    /**
     * set query sort order
     *
     * @param array $orders
     * @return FilterTagsService
     */
    public function sortBy(array $orders = ['title' => 'asc']): FilterTagsService
    {
        foreach ($orders as $orderBy => $order) {
            $this->query->orderBy($orderBy, $order);
        }

        return $this;
    }

    /**
     * get latest tags
     *
     * @return FilterTagsService
     */
    public function latest(): FilterTagsService
    {
        $this->query->latest();
        return $this;
    }

    /**
     * get filtered tags collection
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * get filtered tags collection with pagination
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * get filtered tags count
     *
     * @return int
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * get number of tags
     *
     * @param int $total
     * @return FilterTagsService
     */
    public function take(int $total): FilterTagsService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single tag
     *
     * @return Tag
     */
    public function first(): Tag
    {
        return $this->query->first();
    }

    /**
     * get single tag
     *
     * @return Tag
     */
    public function firstOrFail(): Tag
    {
        return $this->query->firstOrFail();
    }
}
