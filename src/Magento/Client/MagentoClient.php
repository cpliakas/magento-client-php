<?php

namespace Magento\Client;

use Guzzle\Common\Collection;
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
            'base_path' => '/api/rest/',
        );

        $required = array(
            'base_url',
            'base_path',
            'consumer_key',
            'consumer_secret',
            'token',
            'token_secret',
        );

        $config = Collection::fromConfig($config, $defaults, $required);

        $magento = new static($config->get('base_url'), $config);
        $magento->addSubscriber(new MagentoOauthPlugin($config->toArray()));

        return $magento;
    }
}
