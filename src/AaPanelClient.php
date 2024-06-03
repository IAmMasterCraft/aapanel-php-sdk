<?php
namespace Mastercraft\AapanelPhpSdk;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Mastercraft\AapanelPhpSdk\Authentication\TokenManager;
use Mastercraft\AapanelPhpSdk\Exception\APIException;
use InvalidArgumentException;

class AaPanelClient {
    private $tokenManager;
    private $client;
    private $cookieJar;

    public function __construct($baseUri, $apiKey)
    {
        $this->tokenManager = new TokenManager($apiKey);
        $this->client = new Client(['base_uri' => $baseUri]);
        $this->cookieJar = new CookieJar();
    }

    public function post($urlKey, $data = [])
    {

        try {
            $auth = $this->tokenManager->generateToken();
            $data = array_merge($data, $auth);
            $url = ApiEndpointsManager::getURL($urlKey);
        } catch (InvalidArgumentException $e) {
            throw new APIException("Invalid API endpoint key: " . $urlKey, $e->getCode(), $e);
        }

        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
                'cookies' => $this->cookieJar
            ]);
            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['error'])) {
                throw new APIException($responseBody['error']);
            }

            return $responseBody;
        } catch (RequestException $e) {
            throw new APIException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
