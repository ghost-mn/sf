<?php


namespace Sf;

use Sf\Base\AbstractAPI;
class Api extends AbstractAPI
{
    private $appKey;
    private $secret;

    public function signature(array $params)
    {
        ksort($params);
        $waitSign = '';
        foreach ($params as $key => $item) {
            if ($item) {
                $waitSign .= $key . $item;
            }
        }
        return strtolower(sha1($this->secret . $waitSign));
    }

    public function request(string $method, array $params)
    {
        $params = array_merge($params, [
            'appkey' => $this->appKey,
            'timestamp' => time(),
            'version' => '1.0',
        ]);

        $params['sign'] = $this->signature($params);
        $http = $this->getHttp();
        $response = $http->post(selv::URL . $method, $params);
        return json_decode(strval($response->getBody()), true);
    }

}