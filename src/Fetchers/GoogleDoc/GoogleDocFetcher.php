<?php

namespace Stock\Fetchers\GoogleDoc;

use Exception;
use Google_Client;
use Google_Service_Sheets;
use Stock\Fetchers\Fetcher;
use Stock\Fetchers\FetchResult;
use Stock\Parsers\Parser;

class GoogleDocFetcher implements Fetcher
{
    public function __construct(
        private Parser $parser,
        private array $config
    )
    {
    }

    protected string $privateDir = 'app';

    public function fetch(): FetchResult
    {
        $client = $this->getClient();
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = $this->config['spreadsheet_id'];
        $range = $this->config['range'];

        return $this->parser->parse($service->spreadsheets_values->get($spreadsheetId, $range)->getValues());
    }

    protected function getClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets data updater');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setAuthConfig(storage_path($this->privateDir . '/credentials.json'));

        $redirect_uri = request()->fullUrl();
        $client->setRedirectUri($redirect_uri);

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = storage_path($this->privateDir . 'token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function hasDifferences(): bool
    {
        return true;
    }
}
