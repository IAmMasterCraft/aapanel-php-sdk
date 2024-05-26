<?php

namespace AaPanelSDK\Services;

use AaPanelSDK\AaPanelClient;

class Domain
{
    private $client;

    public function __construct(AaPanelClient $client)
    {
        $this->client = $client;
    }

    public function listDomains($siteId)
    {
        return $this->client->post('listDomains', ['search' => $siteId, 'list' => true]);
    }

    public function addDomain($siteId, $domain)
    {
        return $this->client->post('addDomain', [
            'id' => $siteId,
            'webname' => $domain['webname'],
            'domain' => $domain['domain']
        ]);
    }

    public function deleteDomain($siteId, $domain)
    {
        return $this->client->post('deleteDomain', [
            'id' => $siteId,
            'webname' => $domain['webname'],
            'domain' => $domain['domain'],
            'port' => $domain['port']
        ]);
    }
}