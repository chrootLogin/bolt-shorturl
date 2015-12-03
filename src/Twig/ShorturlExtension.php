<?php

namespace Bolt\Extension\rootLogin\Shorturl\Twig;

use Bolt\Application;
use Bolt\Extension\rootLogin\Shorturl\Extension;
use Twig_Extension;

class ShorturlExtension extends Twig_Extension {

    /** @var Application */
    private $app;
    /** @var array */
    private $config;

    public function __construct(Application $app)
    {
        $this->app      = $app;
        $this->config   = $this->app[Extension::CONTAINER]->config;
    }

    /**
     * The functions we add
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('generateShorturl', array($this, 'generateShorturl'), array('is_safe' => array('html'), 'is_safe_callback' => true)),
        );
    }

    /**
     * Generate a shorturl
     *
     * @return string
     */
    public function generateShorturl()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    /**
     * Return the name of the extension
     */
    public function getName()
    {
        return Extension::CONTAINER;
    }
}