<?php

namespace Magento\Client;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\QueryString;
use Guzzle\Http\Url;
use Guzzle\Plugin\Oauth\OauthPlugin;

class MagentoOauthPlugin extends OauthPlugin
{
    /**
     * {@inheritdoc}
     *
     * Magento doesn't understand running on alternate ports. Therefore we have
     * to strip the port. WTF.
     *
     * @see
     */
    public function getStringToSign(RequestInterface $request, $timestamp, $nonce)
    {
        $params = $this->getParamsToSign($request, $timestamp, $nonce);

        // Convert booleans to strings.
        $params = $this->prepareParameters($params);

        // Build signing string from combined params
        $parameterString = new QueryString($params);

        // Remove the port to get around the Magento bug.
        $requestUrl = $request->getUrl(true);
        $requestUrl->setPort('');

        $url = Url::factory($requestUrl->setQuery('')->setFragment(null));

        return strtoupper($request->getMethod()) . '&'
             . rawurlencode($url) . '&'
             . rawurlencode((string) $parameterString);
    }
}