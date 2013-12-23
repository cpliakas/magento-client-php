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
            'base_path' => '/',
        );

        $required = array(
            'base_url',
            'base_path',
            'consumer_key',
            'consumer_secret',
        );

        $config = Collection::fromConfig($config, $defaults, $required);

        $magento = new static($config->get('base_url'), $config);
        $magento->addSubscriber(new MagentoOauthPlugin($config->toArray()));

        $magento->setDefaultOption('headers', array(
            'Content-Type' => 'application/json',
            'Accept' => '*/*',
        ));

        return $magento;
    }

    /**
     * @return \Magento\Client\RequestToken
     *
     * @throws \Magento\Client\OauthException
     */
    public function getRequestToken()
    {
        try {
            $response = $this->post('/oauth/initiate')->send();
        } catch (ClientErrorResponseException $e) {
            throw OauthException::factory($e->getRequest(), $e->getResponse());
        }
        return new RequestToken($this, $response);
    }

    /**
     * @return \Magento\Client\AccessToken
     *
     * @throws \Magento\Client\OauthException
     */
    public function getAccessToken()
    {
        try {
            $response = $this->post('/oauth/token')->send();
        } catch (ClientErrorResponseException $e) {
            throw OauthException::factory($e->getRequest(), $e->getResponse());
        }
        return new AccessToken($this, $response);
    }

    public function products()
    {
        return $this->get('/api/rest/products')->send();
    }
}
