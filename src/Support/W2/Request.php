<?php

declare(strict_types = 1);

namespace App\Support\W2;

/**
 * The finder request.
 */
final class Request
{
    public const LIMIT = 100;

    public int $offset;

    public int $limit;

    public int $searchLogic;

    public array $search;

    public array $sort;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->offset = 0;
        $this->limit = self::LIMIT;
        $this->searchLogic = SearchLogic::AND;
        $this->search = [];
        $this->sort = [];
    }

    /**
     * Set limit.
     *
     * @param int $limit The limit
     *
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit > 0 ? $limit : self::LIMIT;

        return $this;
    }

    /**
     * Set SearchLogic.
     *
     * @param int $searchLogic The search logic
     *
     * @return self
     */
    public function setSearchLogic(int $searchLogic): self
    {
        $this->searchLogic = in_array($searchLogic, [SearchLogic::AND, SearchLogic::OR])
            ? $searchLogic : SearchLogic::AND;

        return $this;
    }

    /**
     * Set offset.
     *
     * @param int $offset The offset
     *
     * @return self
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset > 0 ? $offset : 0;

        return $this;
    }

    /**
     * Add a search object.
     *
     * @param Search $search The search object
     *
     * @return self
     */
    public function addSearch(Search $search): self
    {
        $this->search[] = $search;

        return $this;
    }

    /**
     * Add a sort object.
     *
     * @param Sort $sort The sort object
     *
     * @return self
     */
    public function addSort(Sort $sort): self
    {
        $this->sort[] = $sort;

        return $this;
    }
}
