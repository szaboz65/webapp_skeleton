<?php

declare(strict_types = 1);

namespace App\Support\W2;

use Cake\Validation\Validator;

/**
 * The parser for the finder request.
 */
final class RequestParser
{
    private const DIRECTIONS = [
        'asc' => Sort::ASC,
        'desc' => Sort::DESC,
    ];

    private const TYPES = [
        'int' => Types::INT,
        'text' => Types::TEXT,
        'list' => Types::LIST,
        'enum' => Types::ENUM,
    ];

    private const OPERATORS = [
        '=' => Operators::EQ,
        'is' => Operators::EQ,
        '!=' => Operators::NE,

        '>' => Operators::GT,
        'more' => Operators::GT,
        '<' => Operators::LT,
        'less' => Operators::LT,
        '>=' => Operators::GE,
        '<=' => Operators::LE,
        'between' => Operators::BETWEEN,

        'begins' => Operators::BEGINS,
        'ends' => Operators::ENDS,
        'contains' => Operators::CONTAINS,

        'isnull' => Operators::ISNULL,
        'isnotnull' => Operators::ISNOTNULL,
        'in' => Operators::INLIST,
        'not in' => Operators::NOTINLIST,
    ];

    private const SEARCHLOGIC = [
        'and' => SearchLogic::AND,
        'or' => SearchLogic::OR,
    ];

    /**
     * Parse sort data.
     *
     * @param array $data The data
     *
     * @throws \DomainException
     *
     * @return Sort
     */
    public function parseSort(array $data): Sort
    {
        $errors = $this->createSortValidator()->validate($data);
        if ($errors) {
            throw new \DomainException('Invalid sort data');
        }

        return new Sort([
            'field' => $data['field'],
            'direction' => self::DIRECTIONS[$data['direction']],
        ]);
    }

    /**
     * Create sort validator.
     *
     * @return Validator The validator
     */
    private function createSortValidator(): Validator
    {
        return (new Validator())
            ->requirePresence('field')
            ->notEmptyString('field')
            ->requirePresence('direction')
            ->inList('direction', array_keys(self::DIRECTIONS));
    }

    /**
     * Parse search ata.
     *
     * @param array $data The data
     *
     * @throws \DomainException
     *
     * @return Search
     */
    public function parseSearch(array $data): Search
    {
        $errors = $this->createSearchValidator()->validate($data);
        if ($errors || ($data['type'] == 'enum' && !isset($data['svalue']))) {
            throw new \DomainException('Invalid search data');
        }

        return new Search([
            'field' => $data['field'],
            'type' => self::TYPES[$data['type']],
            'operator' => self::OPERATORS[$data['operator']],
            'value' => $data['type'] == 'enum' ? $data['svalue'] : $data['value'],
        ]);
    }

    /**
     * Create search validator.
     *
     * @return Validator The validator
     */
    private function createSearchValidator(): Validator
    {
        return (new Validator())
            ->requirePresence('field')
            ->notEmptyString('field')
            ->requirePresence('type')
            ->inList('type', array_keys(self::TYPES))
            ->requirePresence('operator')
            ->inList('operator', array_keys(self::OPERATORS))
            ->requirePresence('value');
    }

    /**
     * Parse request.
     *
     * @param array $data The data
     *
     * @throws \DomainException
     *
     * @return Request
     */
    public function parseRequest(array $data): Request
    {
        $errors = $this->createRequestValidator()->validate($data);
        if ($errors) {
            throw new \DomainException('Invalid request data');
        }

        $request = new Request();
        if (isset($data['limit'])) {
            $request->setLimit($data['limit']);
        }
        if (isset($data['offset'])) {
            $request->setOffset($data['offset']);
        }
        if (isset($data['searchLogic'])) {
            $request->setSearchLogic(self::SEARCHLOGIC[strtolower($data['searchLogic'])]);
        }
        if (isset($data['search'])) {
            foreach ($data['search'] as &$search) {
                $request->addSearch($this->parseSearch((array)$search));
            }
        }
        if (isset($data['sort'])) {
            foreach ($data['sort'] as &$sort) {
                $request->addSort($this->parseSort((array)$sort));
            }
        }

        return $request;
    }

    /**
     * Create request validator.
     *
     * @return Validator The validator
     */
    private function createRequestValidator(): Validator
    {
        return (new Validator())
            ->notEmptyString('limit')
            ->greaterThan('limit', 0)
            ->notEmptyString('offset')
            ->greaterThanOrEqual('offset', 0);
    }
}
