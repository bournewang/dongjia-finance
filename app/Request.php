<?php
namespace App;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
class Request
{
    private $api;
    private $header;

    public function __construct($base_uri, $headers = [])
    {
        $this->api = new Client([
            'base_uri' => $base_uri,
            'timeout'  => 5.0,
        ]);
        $this->header = $headers;
    }

    private function responseToJson($response)
    {
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode($response->getBody()->getContents(), 1);
        }else{
            \Log::error($response->getStatusCode());
        }
    }

    public function get($url, $data = [])
    {
        try {
            $res = $this->api->get($url,    ['headers'=> $this->header, 'query' => $data]);
            return $this->responseToJson($res);
        } catch(ClientException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function post($url, $data, $json = false)
    {
        try{
            $data_key = $json ? 'json' : 'form_params';
            $res = $this->api->post($url,   ['headers'=> $this->header, $data_key => $data]);
            return $this->responseToJson($res);
        } catch(ClientException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function put($url, $data)
    {
        try{
            $res = $this->api->put($url,    ['headers'=> $this->header, 'form_params' => $data]);
            return $this->responseToJson($res);
        } catch(ClientException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function patch($url, $data)
    {
        try{
            $res = $this->api->patch($url,  ['headers'=> $this->header, 'json' => $data]);
            return $this->responseToJson($res);
        } catch(ClientException $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function delete($url)
    {
        try{
            $res = $this->api->delete($url,  ['headers'=> $this->header]);
            return $this->responseToJson($res);
        } catch(ClientException $e){
            throw new \Exception($e->getMessage());
        }
    }
}
