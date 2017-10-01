<?php

namespace Elasticsearch\Service;

class ElasticSearchManager
{
    private $clientBuilder;
    private $hosts;
    private $enable; // switch on/off elastic search

    public function __construct($clientBuilder, $host)
    {
        $this->clientBuilder = $clientBuilder;
        $this->hosts = $host;
        $config = new \Zend\Config\Config(include PATH_CONFIG . '/autoload/local.php');
        $this->enable = $config->elasticsearch->enable;
    }

    public function getClient()
    {
        return $this->clientBuilder->setHosts($this->hosts)->build();
    }

    public function createIndex($index)
    {
        $params = [
            'index' => $index,
            'client' => ['ignore' => [400, 404]],
        ];

        if ($this->enable) {
            $response = $this->getClient()->indices()->create($params);
            return $response;
        } else {
            return 'elastic search is disable';
        }
    }

    public function deleteIndex($index)
    {
        $params = [
            'index' => $index,
            'client' => ['ignore' => [400, 404]],
        ];

        if ($this->enable) {
            $response = $this->getClient()->indices()->delete($params);
            return $response;
        } else {
            return 'elastic search is disable';
        }
    }

    public function index($index, $type, $id, $data)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $data,
            'client' => ['ignore' => [400, 404]]
        ];

        if ($this->enable) {
            return $this->getClient()->index($params);
        } else {
            return 'elastic search is disable';
        }
    }

    public function update($index, $type, $id, $data)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => [
                'doc' => $data
            ]
        ];

        if ($this->enable) {
            return $this->getClient()->update($params);
        } else {
            return 'elastic search is disable';
        }
    }

    public function delete($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'client' => ['ignore' => 404],
        ];

        if ($this->enable) {
            return $this->getClient()->delete($params);
        } else {
            return 'elastic search is disable';
        }
    }

    public function get($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'client' => ['ignore' => 404],
        ];
        if ($this->enable) {
            return $this->getClient()->get($params);
        } else {
            return 'elastic search is disable';
        }
    }
}
