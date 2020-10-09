<?php


namespace Sf;

class SaleOrder extends Api
{
    public function saleOrderService(array $params)
    {
        //处理参数
        return $this->request('SALE_ORDER_SERVICE', $params);
    }
}