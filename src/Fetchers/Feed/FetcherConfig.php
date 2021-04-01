<?php

namespace Stock\Fetchers\Feed;

class FetcherConfig
{
    private string $url;
    private string $filename;
    private string $previousFilename;

    public function __construct(string $url, string $filename, string $previousFilename)
    {
        $this->url = $url;
        $this->filename = $filename;
        $this->previousFilename = $previousFilename;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPreviousFilename(): string
    {
        return $this->previousFilename;
    }
}
