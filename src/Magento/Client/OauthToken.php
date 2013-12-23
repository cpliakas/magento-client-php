<?php

namespace Magento\Client;

use Guzzle\Http\Message\Response;
use Guzzle\Http\Url;

class OauthToken
{
    /**
     * @var \Magento\Client\MagentoClient
     */
    protected $client;

    /**
     * @var \Guzzle\Http\Message\Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $tokenSecret;

    /**
     * @var bool
     */
    protected $callbackConfirmed;

    /**
     * @param \Guzzle\Http\Message\Response $response
     *
     * @throws \UnexpectedValueException
     */
    public function __construct(MagentoClient $client, Response $response)
    {
        $this->client = $client;
        $this->response = $response;

        parse_str($response->getBody(true), $arr);
        if (!isset($arr['oauth_token'])) {
            throw new \UnexpectedValueException('Invalid response: missing oauth_token');
        }
        if (!isset($arr['oauth_token_secret'])) {
            throw new \UnexpectedValueException('Invalid response: missing oauth_token_secret');
        }
        if (!isset($arr['oauth_callback_confirmed'])) {
            throw new \UnexpectedValueException('Invalid response: missing oauth_callback_confirmed');
        }

        $this->token = $arr['oauth_token'];
        $this->tokenSecret = $arr['oauth_token_secret'];
        $this->callbackConfirmed = $arr['oauth_callback_confirmed'];
    }

    /**
     * @return \Magento\Client\MagentoClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return \Guzzle\Http\Message\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * @return bool
     */
    public function callbackConfirmed()
    {
        return $this->callbackConfirmed;
    }

    /**
     * @param string $path
     *
     * @return Guzzle\Http\Url
     */
    public function getAuthUrl($path)
    {
        return Url::factory($this->client->getConfig('base_url'))
            ->setPath($path)
            ->setQuery(array('oauth_token' => $this->token))
        ;
    }

    /**
     * @return Guzzle\Http\Url
     */
    public function getCustomerAuthUrl()
    {
        return $this->getAuthUrl('/oauth/authorize');
    }

    /**
     * @return Guzzle\Http\Url
     */
    public function getAdminAuthUrl()
    {
        return $this->getAuthUrl('/admin/oAuth_authorize');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->response->getBody(true);
    }
}
