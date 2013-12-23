<?php

namespace Magento\Client;

use Guzzle\Http\Message\Response;

class OauthToken
{
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
    public function __construct(Response $response)
    {
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
     * @return string
     */
    public function __toString()
    {
        return $this->response->getBody(true);
    }
}
