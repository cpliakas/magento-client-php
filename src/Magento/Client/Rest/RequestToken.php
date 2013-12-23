<?php

namespace Magento\Client\Rest;

use Guzzle\Http\Url;

class RequestToken extends Token
{
    /**
     * @var bool
     */
    protected $callbackConfirmed;

    /**
     * {@inheritdoc}
     *
     * @throws \UnexpectedValueException
     */
    public function init(array $arr)
    {
        if (!isset($arr['oauth_callback_confirmed'])) {
            throw new \UnexpectedValueException('Invalid response: missing oauth_callback_confirmed');
        }
        $this->callbackConfirmed = $arr['oauth_callback_confirmed'];
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
        return $this->getAuthUrl('/admin/oauth_authorize');
    }
}
