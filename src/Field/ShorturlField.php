<?php

namespace Bolt\Extension\rootLogin\Shorturl\Field;

use Bolt\Field\FieldInterface;

class ShorturlField implements FieldInterface
{
    public function getName()
    {
        return 'shorturl';
    }

    public function getTemplate()
    {
        return '_shorturl.twig';
    }

    public function getStorageType()
    {
        return 'text';
    }

    public function getStorageOptions()
    {
        return array('default' => '');
    }
}
