<?php

namespace Magento\Client\Rest;

use Guzzle\Common\Collection;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Service\Client;

class MagentoRestClient extends Client
{
    /**
     * {@inheritdoc}
     *
     * @return \Magento\Client\Rest\MagentoRestClient
     */
    public static function factory($config = array())
    {
        $defaults = array(
            'base_url' => 'http://localhost',
            'base_path' => '',
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
     * {@inheritdoc}
     *
     * Prepends the {+base_path} expressions to the URI
     */
    public function createRequest($method = 'GET', $uri = null, $headers = null, $body = null, array $options = array())
    {
        $uri = '{+base_path}/' . ltrim($uri, '/');
        return parent::createRequest($method, $uri, $headers, $body, $options);
    }

    /**
     * @return \Magento\Client\Rest\RequestToken
     *
     * @throws \Magento\Client\Rest\OauthException
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
     * @return \Magento\Client\Rest\AccessToken
     *
     * @throws \Magento\Client\Rest\OauthException
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

    /**
     * @return array
     *
     * @see http://www.magentocommerce.com/api/rest/Resources/Products/products.html#RESTAPI-Resource-Products-HTTPMethod-GET-products
     */
    public function products()
    {
        return $this->get('/api/rest/products')->send()->json();
    }
}
