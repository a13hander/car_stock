<?php

namespace Stock\Parsers;

use Stock\Fetchers\FetchResult;
use Stock\Validation\Validator;

abstract class Parser
{
    abstract public function parse($filename): FetchResult;

    public function __construct(
        protected Validator $validator,
        protected array $fieldsMap
    )
    {
    }
}
