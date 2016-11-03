<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 17/10/16
 * Time: 18:32
 */

namespace Enhavo\Bundle\NewsletterBundle\CleverReach;


use GuzzleHttp\Exception\ClientException;

class Client
{
    const REST_BASE_URI = 'https://rest.cleverreach.com/v2/';

    protected $guzzleClient;
    protected $credentials;
    protected $groupId;

    public function __construct($credentials, $groupId)
    {
        $this->guzzleClient = new \GuzzleHttp\Client(['base_uri' => Client::REST_BASE_URI]);
        $this->credentials = $credentials;
        $this->groupId = $groupId;
    }

    public function saveSubscriber($eMail)
    {
        $token = $this->getToken();

        $groupId = $this->groupId;

        $data = [
            'postdata' => [
                [
                    'email' => $eMail
                ],
            ],
        ];

        $res = $this->guzzleClient->request('POST', 'groups.json/'. $groupId .'/receivers/insert' . '?token=' . $token, [
            'json' => $data
        ]);
        $response = $res->getBody()->getContents();

        if(json_decode($response) === ['insert success;']) {
            return true;
        }
        return false;
    }

    public function exists($eMail)
    {
        $token = $this->getToken();

        $groupId = $this->groupId;


        try {
            $res = $this->guzzleClient->request('GET', 'groups.json/' . $groupId . '/receivers/' . $eMail . '?token=' . $token, []);
        } catch (ClientException $e){ // e.g. 404 for "user not found"
            return false;
        }
        $response = $res->getBody()->getContents();
        if(json_decode($response)->email === $eMail){
            return true;
        }
        return false;
    }

    protected function getToken()
    {
        $res = $this->guzzleClient->request('POST', 'login', [
            'json' => [
                'client_id' => $this->credentials['client_id'],
                'login' => $this->credentials['login'],
                'password' => $this->credentials['password']
            ]
        ]);
        $tokenWithQuotationMarks = $res->getBody()->getContents();
        return substr($tokenWithQuotationMarks, 1, -1);
    }
}