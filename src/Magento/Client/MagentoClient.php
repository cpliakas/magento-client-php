<?php

namespace Magento\Client;

use Guzzle\Common\Collection;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Service\Client;

class MagentoClient extends Client
{
    /**
     * {@inheritdoc}
     *
     * @return \Magento\Client\MagentoClient
     */
    public static function factory($config = array())
    {
        $defaults = array(
            'base_url' => 'http://localhost',
        );

        $required = array(
            'base_url',
            'consumer_key',
            'consumer_secret',
        );

        $config = Collection::fromConfig($config, $defaults, $required);

        $magento = new static($config->get('base_url'), $config);
        $magento->addSubscriber(new MagentoOauthPlugin($config->toArray()));

        return $magento;
    }

    /**
     * @return \Magento\Client\OauthToken
     *
     * @throws \Magento\Client\OauthException
     */
    public function getToken()
    {
        try {
            $response = $this->post('/oauth/initiate')->send();
        } catch (ClientErrorResponseException $e) {
            throw OauthException::factory($e->getRequest(), $e->getResponse());
        }
        return new OauthToken($this, $response);
    }
}
