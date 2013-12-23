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
     * @see http://stackoverflow.com/a/14693714/870667
     */
    public function getStringToSign(RequestInterface $request, $timestamp, $nonce)
    {
        $params = $this->getParamsToSign($request, $timestamp, $nonce);

        // Convert booleans to strings.
        $params = $this->prepareParameters($params);

        // Build signing string from combined params
        $parameterString = new QueryString($params);

        // Remove the port to get around the Magento bug.
        // @see http://stackoverflow.com/a/14693714/870667
        $requestUrl = $request->getUrl(true);
        if (preg_match('@/oauth/(initiate|token)$@', $requestUrl->getPath())) {
            $requestUrl->setPort('');
        }

        $url = Url::factory($requestUrl->setQuery('')->setFragment(null));

        return strtoupper($request->getMethod()) . '&'
             . rawurlencode($url) . '&'
             . rawurlencode((string) $parameterString);
    }
}
