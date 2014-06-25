<?php
namespace CodeClimate\Bundle\TestReporterBundle;

use GuzzleHttp\Client;
use GuzzleHttp\Stream;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ClientErrorResponseException;

class ApiClient
{
    protected $client;
    protected $apiHost;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiHost = "https://codeclimate.com";

        if (isset($_SERVER["CODECLIMATE_API_HOST"])) {
          $this->apiHost = $_SERVER["CODECLIMATE_API_HOST"];
        }

    }

    public function send($json)
    {
        $request = $this->client->createRequest('POST', $this->apiHost."/test_reports");
        $response = false;

        $request->setHeader("User-Agent", "Code Climate (PHP Test Reporter v".Version::VERSION.")");
        $request->setHeader("Content-Type", "application/json");
        $request->setBody(Stream\create($json));

        try {
            $response = $this->client->send($request);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ClientErrorResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}
