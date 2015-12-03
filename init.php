<?php

use Bolt\Extension\rootLogin\Shorturl\Extension;

if (isset($app)) {
    $app['extensions']->register(new Extension($app));
}
