<?php
namespace Sf;

use Sf\Base\Foundation;

class Dispatch extends Foundation
{
    private $saleOrder;
    private $obj;

    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function __get($type)
    {
        switch ($type) {
            case 'saleOrder':
                if (isset($this->saleOrder)) {
                    $this->obj = $this->saleOrder;
                    return $this->obj;
                } else {
                    $this->obj = new SaleOrder($this->getConfig('AccessCode'), $this->getConfig('CheckWord'));
                    return $this->obj;
                }
                break;
        }
    }

    public function __call($name, $arguments)
    {
        return $this->obj->{$name}(...$arguments);
    }
}