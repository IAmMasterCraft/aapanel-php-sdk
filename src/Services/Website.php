<?php

namespace AaPanelSDK\Services;

use AaPanelSDK\AaPanelClient;

class Website
{
    // Website & Backup class to interact with the website API endpoints
    private $client;

    public function __construct(AaPanelClient $client)
    {
        $this->client = $client;
    }

    public function getSites($page = 1, $limit = 15)
    {
        return $this->client->post('getSites', [
            'p' => $page,
            'limit' => $limit
        ]);
    }

    public function createSite($siteData)
    {
        return $this->client->post('createSite', $siteData);
    }

    public function deleteSite($id, $webname, $options = [])
    {
        $params = array_merge(['id' => $id, 'webname' => $webname], $options);
        return $this->client->post('deleteSite', $params);
    }

    public function listBackups($siteId, $page = 1, $limit = 5)
    {
        return $this->client->post('listBackups', [
            'search' => $siteId,
            'p' => $page,
            'limit' => $limit,
            'type' => 0
        ]);
    }

    public function createBackup($siteId)
    {
        return $this->client->post('createBackup', ['id' => $siteId]);
    }

    public function deleteBackup($backupId)
    {
        return $this->client->post('deleteBackup', ['id' => $backupId]);
    }
}
