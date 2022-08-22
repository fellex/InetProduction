<?php

abstract class RequestService {
    public function request(string $url, string $method, array $options) {}
}

class XMLHttpService extends XMLHTTPRequestService {
    public function getRequestService() {
        $requestService = new RequestService();
        return $requestService;
    }
}

class Http {
    private $service;

    public function __construct(XMLHttpService $xmlHttpService) {
        $this->service = $xmlHttpService->getRequestService();
    }

    public function get(string $url, array $options) {
        $this->service->request($url, 'GET', $options);
    }

    public function post(string $url) {
        $this->service->request($url, 'GET');
    }
}