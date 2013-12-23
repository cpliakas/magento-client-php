<?php

namespace Magento\Client\Rest;

use Guzzle\Http\Message\Response;

class Token
{
    /**
     * @var \Magento\Client\MagentoRestClient
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
     * @param \Magento\Client\MagentoRestClient
     * @param \Guzzle\Http\Message\Response $response
     *
     * @throws \UnexpectedValueException
     */
    public function __construct(MagentoRestClient $client, Response $response)
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

        $this->token = $arr['oauth_token'];
        $this->tokenSecret = $arr['oauth_token_secret'];
    }

    /**
     * @param array $arr
     */
    public function init(array $arr) {}

    /**
     * @return \Magento\Client\MagentoRestClient
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
     * @return string
     */
    public function __toString()
    {
        return $this->response->getBody(true);
    }
}
