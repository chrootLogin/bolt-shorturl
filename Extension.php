<?php

namespace Bolt\Extension\rootLogin\Shorturl;

use Bolt\Application;
use Bolt\BaseExtension;
use Silex\Application as BoltApplication;
use Symfony\Component\HttpFoundation\Request;

class Extension extends BaseExtension
{
    const NAME = 'Shorturl';

    const CONTAINER = 'extensions.Shorturl';

    /**
     * Constructor adds an additional Twig path if we are in the Backend.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->app['config']->getFields()->addField(new Field\ShorturlField());
    }

    public function initialize()
    {
        $this->app['twig.loader.filesystem']->prependPath(__DIR__."/twig");

        $end = $this->app['config']->getWhichEnd();

        $this->app->mount($this->app['config']->get('general/branding/path').'/async/shorturl', new Controller\AsyncController($this->app, $this->config));

        if ($end =='backend') {
            $this->addTwig();
            $this->addCss('assets/css/field_shorturl.css');
            $this->addJavascript('assets/js/field_shorturl.js', true);
        } elseif($end == 'frontend') {
            new Controller\ShorturlController($this->app);
        }
    }

    /**
     * Set the defaults for configuration parameters.
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'maxlength' => 10,
            'prefix' => 's',
            'checkunique' => true,
            'host' => '*',
            'destination' => 'link'
        );
    }

    /**
     * Add the Twig functions.
     */
    private function addTwig()
    {
        $app = $this->app;

        // Safe
        $this->app->share(
            $this->app->extend(
                'twig',
                function (\Twig_Environment $twig) use ($app) {
                    $twig->addExtension(new Twig\ShorturlExtension($app));

                    return $twig;
                }
            )
        );

        // Normal
        $this->app->share(
            $this->app->extend(
                'safe_twig',
                function (\Twig_Environment $twig) use ($app) {
                    $twig->addExtension(new Twig\ShorturlExtension($app));

                    return $twig;
                }
            )
        );
    }

    public function getName()
    {
        return Extension::NAME;
    }
}
