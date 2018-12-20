<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 17.12.18
 * Time: 12:21
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Bundle\MarketplaceBundle\Entity\Grid\StoreItem;
use Enhavo\Bundle\NewsletterBundle\CleverReach\Client;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Exception\NoGroupException;
use Enhavo\Bundle\NewsletterBundle\Group\GroupManager;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Twig\SubscribeFormRenderer;

class MailChimpStorage implements StorageInterface
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * @var String
     */
    protected $apiKey;

    /**
     * @var bool|string
     */
    protected $dataCenter;

    /**
     * @var string[]
     */
    protected $mapping;

    public function __construct($credentials, $mapping)
    {
        $this->apiKey = $credentials['api_key'];
        $this->dataCenter = substr($this->apiKey, strpos($this->apiKey, '-') + 1);
        $this->mapping = $mapping;
        $this->guzzleClient = new \GuzzleHttp\Client(['base_uri' => Client::REST_BASE_URI]);
    }

    public function saveSubscriber(SubscriberInterface $subscriber)
    {
        if (count($subscriber->getGroups()) === 0) {
            throw new NoGroupException('no groups given');
        }

        /** @var Group $group */
        foreach($subscriber->getGroups() as $group) {
            $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' . $this->mapping[$group->getCode()] . '/members';

            $data = json_encode([
                'email_address' => $subscriber->getEmail(),
                'status' => 'subscribed',
            ]);

            // send a HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['content-type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        }
    }

    public function exists(SubscriberInterface $subscriber)
    {
        if (count($subscriber->getGroups()) === 0) {
            return false;
        }

        /** @var Group $group */
        foreach ($subscriber->getGroups() as $group) {
            $memberID = md5(strtolower($subscriber->getEmail()));
            $url = 'https://' . $this->dataCenter . '.api.mailchimp.com/3.0/lists/' . $this->mapping[$group->getCode()] . '/members/' . $memberID;

            // send a HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($httpCode == '404') {
                return false;
            }
        }
        return true;
    }

    public function getType()
    {
        return 'mailchimp';
    }
}