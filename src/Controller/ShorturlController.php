<?php

namespace Bolt\Extension\rootLogin\Shorturl\Controller;

use Bolt\Extension\rootLogin\Shorturl\Extension;
use Bolt\Routing\ControllerCollection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;

class ShorturlController {

    /**
     * Sets up all the named routes for the extension.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->config = $app[Extension::CONTAINER]->config;

        /** @var \Symfony\Component\Routing\RouteCollection $routes */
        $routes = $app['routes'];

        $route = (new Route('/' . $this->config['prefix'] . '/{shorturl}'))
            ->setMethods('GET')
            ->setDefault('_controller',array($this, 'shorturlAction'));

        if($this->config['host'] != "*" && $app['debug'] != true) {
            $route->setHost($this->config['host']);
        }

        $routes->add('shorturl', $route);
    }

    /**
     * Shorturl Controller
     *
     * @param Request            $request
     * @param \Silex\Application $app
     * @param string             $shorturl
     *
     * @return RedirectResponse
     */
    public function shorturlAction(Request $request, Application $app, $shorturl)
    {
        /** @var \Bolt\Storage $storage */
        $storage = $app['storage'];
        $res = $storage->searchAllContentTypes(['shorturl' => $shorturl]);

        if(count($res) > 0) {
            /** @var \Bolt\Content $content */
            $content = $res[0];

            if($this->config['destination'] == 'link') {
                $url = $content->link();
            } else {
                $url = $content->get($this->config['destination']);
            }

            if($url != null) {
                return new RedirectResponse($url);
            }
        }

        throw new NotFoundHttpException();
    }
}