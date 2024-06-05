# aaPanel PHP SDK

A PHP SDK for interacting with the aaPanel API. This SDK provides convenient methods for managing your aaPanel server, including system status, website management, backup management, domain management, pseudo-static rules, and logs.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [Initializing the Client](#initializing-the-client)
  - [System Service](#system-service)
  - [Website Service](#website-service)
  - [Backup Service](#backup-service)
  - [Domain Service](#domain-service)
  - [Pseudo-Static Service](#pseudo-static-service)
  - [Log Service](#log-service)
- ['How To' Guides](#'How-To'-Guides)
  - [How to retrieve list of all existing websites](#How-to-retrieve-list-of-all-existing-websites)
  - [How to add new domain or url to existing websites](#How-to-add-new-domain-or-url-to-existing-websites)
- [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

## Installation

You can install the SDK via Composer. Run the following command:

```bash
composer require mastercraft/aapanel-php-sdk
```

## Usage

### Initializing the Client

To get started, you need to initialize the AaPanelClient with your aaPanel base URL and API key. It is good practice to set the AAPANEL_URL and AAPANEL_API_KEY values through your environment variables.

<b>examples/demo.php:</b>

```php
require __DIR__ . '/../vendor/autoload.php';

use Mastercraft\AapanelPhpSdk\AaPanelClient;
use Mastercraft\AapanelPhpSdk\Services\System;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$baseUri = $_ENV['AAPANEL_URL'];
$apiKey = $_ENV['AAPANEL_API_KEY'];

$client = new AaPanelClient($baseUri, $apiKey);

// Example usage
$system = new System($client);
$systemInfo = $system->getSystemTotal();
print_r($systemInfo);
```

### System Service

The `System` service allows you to retrieve system status information.

```php
use Mastercraft\AapanelPhpSdk\Services\System;

$system = new System($client);

// Get system info
$systemInfo = $system->getSystemTotal();
print_r($systemInfo);

// Get disk information
$diskInfo = $system->getDiskInfo();
print_r($diskInfo);

// Get real-time network status
$networkStatus = $system->getNetwork();
print_r($networkStatus);

// Check for updates
$updateStatus = $system->checkUpdate();
print_r($updateStatus);
```

### Website Service

The `Website` service allows you to manage websites on your aaPanel server.

```php
use Mastercraft\AapanelPhpSdk\Services\Website;

$website = new Website($client);

// Get list of sites
$sites = $website->getSites();
print_r($sites);

// Create a new site
$newSite = $website->createSite([
    'webname' => '{"domain":"example.com","domainlist":[],"count":0}',
    'path' => '/www/wwwroot/example.com',
    'type_id' => 0,
    'type' => 'PHP',
    'version' => '72',
    'port' => 80,
    'ps' => 'test site',
    'ftp' => true,
    'ftp_username' => 'example_com',
    'ftp_password' => 'password',
    'sql' => true,
    'codeing' => 'utf8',
    'datauser' => 'example_com',
    'datapassword' => 'password'
]);
print_r($newSite);

// Delete a site
$siteId = 66;
$deletedSite = $website->deleteSite($siteId, 'example.com');
print_r($deletedSite);
```

### Backup Service

The `Backup` service allows you to manage website backups.

```php
use Mastercraft\AapanelPhpSdk\Services\Backup;

$backup = new Backup($client);

// List backups for a site
$backups = $backup->listBackups($siteId);
print_r($backups);

// Create a new backup
$newBackup = $backup->createBackup($siteId);
print_r($newBackup);

// Delete a backup
$backupId = 121;
$deletedBackup = $backup->deleteBackup($backupId);
print_r($deletedBackup);
```

### Domain Service

The `Domain` service allows you to manage domains for your websites.

```php
use Mastercraft\AapanelPhpSdk\Services\Domain;

$domain = new Domain($client);

// List domains for a site
$domains = $domain->listDomains($siteId);
print_r($domains);

// Add a new domain to a site
$newDomain = $domain->addDomain($siteId, ['webname' => 'example.com', 'domain' => 'new.example.com']);
print_r($newDomain);

// Delete a domain from a site
$deletedDomain = $domain->deleteDomain($siteId, ['webname' => 'example.com', 'domain' => 'new.example.com', 'port' => 80]);
print_r($deletedDomain);
```

### Pseudo-Static Service

The `PseudoStatic` service allows you to manage pseudo-static rules.

```php
use Mastercraft\AapanelPhpSdk\Services\PseudoStatic;

$pseudoStatic = new PseudoStatic($client);

// Get pseudo-static rewrite list
$rewriteList = $pseudoStatic->getRewriteList('example.com');
print_r($rewriteList);

// Get rewrite rule content
$rewriteRule = $pseudoStatic->getRewriteRule('/www/server/panel/vhost/rewrite/nginx/example.com.conf');
print_r($rewriteRule);

// Save rewrite rule content
$savedRewriteRule = $pseudoStatic->saveRewriteRule('/www/server/panel/vhost/rewrite/nginx/example.com.conf', 'new content');
print_r($savedRewriteRule);
```

### Log Service

The `Log` service allows you to retrieve logs.

```php
use Mastercraft\AapanelPhpSdk\Services\Log;

$log = new Log($client);

// Get logs
$logLimit = 10;
$logs = $log->getLogs($logLimit);
print_r($logs);
```

## 'How To' Guides
This section contains directions and explanations on how to perform certain actions

### How to retrieve list of all existing websites
Using the `Website` Service, call the `getSites();` method.
Additional parameters (`$page` and `$limit`) can be passed for filtering and pagination
<b>Response:</b>
```json
{
    "where",
    "page",
    "data": [
        {
            "id",
            "name",
            "path",
            "status",
            "domain",
            ...
        },
        ...
    ],
    "search_history",
    "net_flow_info"
}
```
### How to add new domain or url to existing websites
Using the `Domain` Service, call the `addDomain($siteId, $domain);` method.
<b>Parameters:</b>
- `$siteId:` is a unique identifier for the website that this new domain will be pointing to, in order to get the`$siteId`, check the reference on [How to retrieve list of all existing websites](#How-to-retrieve-list-of-all-existing-websites)
- `$domain:` is an array that holds the `webname` of the site we want to point to and `domain` is the new url we are adding. 
<b>Response:</b>
```json
{
    "status",
    "message"
}
```

## Running Tests

To run the tests, use the following command:

```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

### Example Test Suite

<b>tests/AaPanelClientTest.php:</b>

```php
namespace Mastercraft\AapanelPhpSdk\Tests;

use Mastercraft\AapanelPhpSdk\AaPanelClient;
use Mastercraft\AapanelPhpSdk\Exception\APIException;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class AaPanelClientTest extends TestCase
{
    private $apiKey;
    private $baseUri;

    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->safeLoad();
        $this->apiKey = $_ENV['AAPANEL_API_KEY'];
        $this->baseUri = $_ENV['AAPANEL_URL'];
    }

    public function testPost()
    {
        $aaPanelClient = new AaPanelClient($this->baseUri, $this->apiKey);
        $response = $aaPanelClient->post('getSystemTotal');

        $this->assertEquals(['status' => 'success'], $response);
    }

    public function testPostInvalidEndpoint()
    {
        $this->expectException(APIException::class);
        
        $aaPanelClient = new AaPanelClient($this->baseUri, $this->apiKey);
        $aaPanelClient->post('invalidKey');
    }
}
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss your ideas.

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.