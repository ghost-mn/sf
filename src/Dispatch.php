<?php
namespace Sf;

use Sf\Base\Foundation;

class Dispatch extends Foundation
{
    private $order;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->order = new Order($config['app_key'], $config['secret']);
    }

    public function __call($name, $arguments)
    {
        return $this->order->{$name}(...$arguments);
    }
}