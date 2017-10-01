<?php

namespace Elasticsearch\Service;

class ElasticSearchManager
{
    private $clientBuilder;
    private $hosts;

    public function __construct($clientBuilder, $host)
    {
        $this->clientBuilder = $clientBuilder;
        $this->hosts = $host;
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

        $response = $this->getClient()->indices()->create($params);

        return $response;
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

        return $this->getClient()->index($params);
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

        return $this->getClient()->update($params);
    }

    public function delete($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'client' => ['ignore' => 404],
        ];

        return $this->getClient()->delete($params);
    }

    public function get($index, $type, $id)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'client' => ['ignore' => 404],
        ];

        return $this->getClient()->get($params);
    }
}
