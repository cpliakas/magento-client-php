<?php

namespace Magento\Client\Xmlrpc;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;

class MagentoXmlrpcClient extends Client
{
    /**
     * @var boolean
     */
    protected $autoCloseSession = false;

    /**
     * @var \fXmlRpc\Client
     */
    private $client;

    /**
     * {@inheritdoc}
     *
     * @return \Magento\Client\Xmlrpc\MagentoXmlrpcClient
     */
    public static function factory($config = array())
    {
        $defaults = array(
            'session' => '',
        );

        $required = array(
            'base_url',
            'api_user',
            'api_key',
            'session',
        );

        // Instantiate the Acquia Search plugin.
        $config = Collection::fromConfig($config, $defaults, $required);
        return new static($config->get('base_url'), $config);
    }

    /**
     * @param bool $autoClose
     *
     * @return \Magento\Client\Xmlrpc\MagentoXmlrpcClient
     */
    public function autoCloseSession($autoClose = true)
    {
        $this->autoCloseSession = $autoClose;
        return $this;
    }

    /**
     * Ends the session if applicable.
     */
    public function __destruct()
    {
        if ($this->autoCloseSession && $this->client) {
            $this->client->call('endSession', array($this->getConfig('session')));
        }
    }

    /**
     * @return \fXmlRpc\Client
     */
    public function getClient()
    {
        if (!isset($this->client)) {
            $uri = rtrim($this->getConfig('base_url'), '/') . '/api/xmlrpc/';
            $bridge = new \fXmlRpc\Transport\GuzzleBridge($this);
            $this->client = new \fXmlRpc\Client($uri, $bridge);
        }

        return $this->client;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        $session = $this->getConfig('session');

        if (!$session) {
            $this->autoCloseSession = true;
            $session = $this->getClient()->call('login', array(
                $this->getConfig('api_user'),
                $this->getConfig('api_key')
            ));
            $this->getConfig()->set('session', $session);
        }

        return $session;
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return array
     *
     * @throws \fXmlRpc\Exception\ResponseException
     */
    public function call($method, array $params = array())
    {
        $params = array($this->getSession(), $method, $params);
        return $this->getClient()->call('call', $params);
    }
}
