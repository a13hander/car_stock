<?php

namespace Stock;

interface Parser
{
    public function parse(string $filename): FetchResult;
}
