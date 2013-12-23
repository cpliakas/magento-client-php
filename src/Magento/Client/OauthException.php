<?php

namespace Magento\Client;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class OauthException extends ClientErrorResponseException
{
    /**
     * Factory method to create a new Oauth exception.
     *
     * @param RequestInterface $request
     * @param Response $response
     *
     * @return OauthException
     */
    public static function factory(RequestInterface $request, Response $response)
    {
        $message = 'Client error response' . PHP_EOL . implode(PHP_EOL, array(
            '[status code] ' . $response->getStatusCode(),
            '[reason phrase] ' . $response->getReasonPhrase(),
            '[url] ' . $request->getUrl(),
        ));

        $e = new static($message);
        $e->setResponse($response);
        $e->setRequest($request);

        return $e;
    }

    /**
     * Returns the Magento problem constant.
     *
     * @return string
     */
    public function getOauthProblem()
    {
        parse_str($this->getResponse()->getBody(true), $arr);
        return isset($arr['oauth_problem']) ? $arr['oauth_problem'] : '';
    }
}
