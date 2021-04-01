<?php

namespace Stock\Fetchers;

interface Fetcher
{
    public function fetch(): FetchResult;

    public function hasDifferences(): bool;
}
