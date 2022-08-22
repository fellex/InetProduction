<?php
class Concept {
    private $client;
    private $secret_key_type = 'db'; // file/db/redis/cloud

    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getUserData() {
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->getSecretKey()
        ];

        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
        });

        $promise->wait();
    }

    private function getSecretKey(): string
    {
        $return = '';
        switch($this->secret_key_type) {
            case 'file':
                $secret_key = new fileSecretKey();
                break;
            case 'db':
                $secret_key = new dbSecretKey();
                break;
            case 'redis':
                $secret_key = new fileSecretKey();
                break;
            case 'cloud':
                $secret_key = new fileSecretKey();
                break;
            case 'db':
                $secret_key = new fileSecretKey();
                break;
        }
        if(!empty($secret_key)) {
            $return = $secret_key->getSecretKey();
        }

        return $return;
    }
}

interface iSecretKey {
    function getSecretKey();
}

class fileSecretKey implements iSecretKey {
    function getSecretKey(): string {
        return $this->getSecretKeyFromFile();
    }

    function getSecretKeyFromFile() {}
}

class dbSecretKey implements iSecretKey {
    function getSecretKey(): string {
        return $this->getSecretKeyFromDB();
    }

    function getSecretKeyFromDB() {}
}

class redisSecretKey implements iSecretKey {
    function getSecretKey(): string {
        return $this->getSecretKeyFromRedis();
    }

    function getSecretKeyFromRedis() {}
}

class cloudSecretKey implements iSecretKey {
    function getSecretKey(): string {
        return $this->getSecretKeyFromCloud();
    }

    function getSecretKeyFromCloud() {}
}
