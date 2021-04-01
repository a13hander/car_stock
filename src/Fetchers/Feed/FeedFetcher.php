<?php

namespace Stock\Fetchers\Feed;

use GuzzleHttp\Client as HttpClient;
use Stock\Fetchers\Fetcher;
use Stock\Fetchers\FetchResult;
use Stock\Parsers\Feed\XmlParser;

class FeedFetcher implements Fetcher
{
    private HttpClient $httpClient;
    private XmlParser $xmlParser;

    private string $url;
    private string $filename;
    private string $previousFilename;

    private bool $sourceUpdated = false;

    public function __construct(HttpClient $httpClient, XmlParser $xmlParser, FetcherConfig $config)
    {
        $this->httpClient = $httpClient;
        $this->xmlParser = $xmlParser;

        $this->url = $config->getUrl();
        $this->filename = $config->getFilename();
        $this->previousFilename = $config->getPreviousFilename();
    }

    public function fetch(): FetchResult
    {
        $this->updateSource();

        return $this->xmlParser->parse($this->filename);
    }

    public function hasDifferences(): bool
    {
        $this->updateSource();

        if (file_exists($this->filename) === false || file_exists($this->previousFilename) === false) {
            return true;
        }

        return sha1_file($this->filename) !== sha1_file($this->previousFilename);
    }

    private function updateSource(): void
    {
        if ($this->sourceUpdated) return;

        $this->backupPreviousFile();
        $this->downloadFile();

        $this->sourceUpdated = true;
    }

    private function backupPreviousFile(): void
    {
        if (file_exists($this->previousFilename)) {
            unlink($this->previousFilename);
        }

        if (file_exists($this->filename)) {
            rename($this->filename, $this->previousFilename);
        }
    }

    private function downloadFile(): void
    {
        $stream = fopen($this->url, 'r');

        file_put_contents($this->filename, $stream);

        fclose($stream);
    }
}


