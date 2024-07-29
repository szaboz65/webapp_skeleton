<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Support;

use App\Support\TemplateEngine;

class FakeTemplateEngine extends TemplateEngine
{
    // access to protected functions
    /**
     * Get params.
     *
     * @param string $string A string
     *
     * @return string
     */
    public function agetParams(string $string): string
    {
        return $this->getParams($string);
    }

    /**
     * Get function prefix.
     *
     * @param string $string A string
     *
     * @return string
     */
    public function agetFuncPrefix(string $string): string
    {
        return $this->getFuncPrefix($string);
    }

    /**
     * Parse include.
     *
     * @param string $statement A statement
     *
     * @return string
     */
    public function aparseInclude(string $statement): string
    {
        return $this->parseInclude($statement);
    }

    /**
     * Parse foreach.
     *
     * @param string $statement A statement
     *
     * @return string
     */
    public function aparseForeach(string $statement): string
    {
        return $this->parseForeach($statement);
    }

    /**
     * Parse if.
     *
     * @param string $statement A statement
     *
     * @return string
     */
    public function aparseIf(string $statement): string
    {
        return $this->parseIf($statement);
    }

    /**
     * Parse var.
     *
     * @param string $statement A statement
     *
     * @return string
     */
    public function aparseVar(string $statement): string
    {
        return $this->parseVar($statement);
    }

    /**
     * Parse template statement.
     *
     * @param string $statement A statement
     *
     * @return string
     */
    public function aparseTemplateStatement(string $statement): string
    {
        return $this->parseTemplateStatement($statement);
    }
}
