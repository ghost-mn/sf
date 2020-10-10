<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Sf\Dispatch;

class TestOrder extends TestCase
{
    protected $secret = '';

    protected $appKey = '';

    /**
     * 创建订单
     *
     */
    public function testSaleOrderService()
    {
        $config = [
            //货主
            'CompanyCode' => 'COMMONCOMPANY',
            //仓库
            'WarehouseCode' => '571DCF',
            //月结卡
            'MonthlyAccount' => '7550626410',
            //access_code
            'AccessCode' => 'bvW2Fcx8Hb1OYzZaL2mY8A==',
            //checkword
            'CheckWord' => 'Pa2zfPtcCIvmhxYkfw5Bj6JgPmx63s4i',
        ];
        $sfOrder = (new Dispatch($config));
        $params = [
            'ItemRequest' => [
                'CompanyCode' => 'COMMONCOMPANY',
                'Items' => [
                    'Item' => [
                        'SkuNo' => 123,
                        'ItemName' => '商品名称'
                    ],
                ],
            ],
        ];
        $sfOrder->saleOrder->saleOrderService($params);
    }
}