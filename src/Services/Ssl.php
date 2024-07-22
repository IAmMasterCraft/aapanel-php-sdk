<?php

namespace Mastercraft\AapanelPhpSdk\Services;

use Mastercraft\AapanelPhpSdk\AaPanelClient;

class Ssl
{
    private $client;

    public function __construct(AaPanelClient $client)
    {
        $this->client = $client;
    }

    public function getSslData()
    {
        return $this->client->post('getSslList');
    }

    public function getSslCertificates()
    {
        return $this->client->post('getDeployedSslCertificates');
    }

    public function getLetsEncryptInfo()
    {
        return $this->client->post('letsEncryptInfo');
    }

    private function formatDomainList($domainList) {
        $result = array_map(function($domain) {
            return $domain['name'];
        }, $domainList);
        $reindexedResult = array_values($result);
        return json_encode($reindexedResult);
    }

    public function applyForCertificate($data)
    {
        /*
            * $data = [
            *      'domains' => ['example.com', 'example2.com']
            *      'auth_type' => 'http' || 'dns'
            *      'auth_to' => 2 || 'dns'
            *      'auto_wildcard' => 0 => false || 1 => true
            *      'id' => $siteId
            * ];

            * $response = [
                cert,
                cert_timeout,
                domains,
                msg: "Application successful!",
                private_key,
                root,
                save_path,
                status: true,
            ];
        */
        $domainString = implode(", ", $data['domains'][0]);
        $test = [["name" => "storedev.top", "id" => "2"], ["name" => "dev-kuhstomshop.com", "id" => "3"]];
        return $this->client->post('applyCert', [
            // 'domains' => $this->formatDomainList($data['domains'][0]),
            'domains' => $this->formatDomainList($test),
            'id' => $data['siteId'],
            'auth_to' => $data['siteId'],
            'auth_type' => 'http',
            'auto_wildcard' => '0',
        ]);
    }

    public function verifyDns($data)
    {
        /*
            * $data = [
                index: 7dd2b405c59ed5fc34ba9a91b23cd260 // from $this->applyForCertificate($data)['index']
            ]
        */
        return $this->client->post('dnsAuth', [
            'index' => $data['index']
        ]);
    }

    public function getRegisteredUserInfo()
    {
        return $this->client->post('getPanelSslUerInfo');
    }

    public function disableSsl($webname, $updateOf=1)
    {
        return $this->client->post('disableSsl', [
            'siteName' => $webname,
            'updateOf' => $updateOf
        ]);
    }

    public function enableSsl($data)
    {
        /*
            * $data = [
            *      'type' => 1
            *      'siteName' => $data['webName']
            *      'key' => $data['key'] // from $response['private_key'] of $this->applyForCertificate()
            *      'csr' => $data['csr'] // from $response['cert'] . ' ' .$response['root'] of $this->applyForCertificate()
            * ];

            * $response = [
                cert,
                cert_timeout,
                domains,
                msg: "Application successful!",
                private_key,
                root,
                save_path,
                status: true,
            ];
        */
        return $this->client->post('setSslCert', [
            'type' => 1,
            'siteName' => $data['webname'],
            'key' => $data['key'],
            'csr' => $data['csr']
        ]);
    }
}
