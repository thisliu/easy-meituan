<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

// 众包
class CrowdSourcing extends Client
{
    /**
     * 批量查询众包配送费
     * https://developer.waimai.meituan.com/home/docDetail/151
     */
    public function shippingFee(string $orderIds): ResponseInterface
    {
        return $this->get('order/zhongbao/shippingFee', ['order_ids' => $orderIds]);
    }

    /**
     * 众包发配送
     * https://developer.waimai.meituan.com/home/docDetail/154
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function dispatch(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'shipping_fee' => 'required|numeric',
            'tip_amount' => 'required|numeric',
        ]);

        return $this->get('order/zhongbao/dispatch', $params);
    }

    /**
     * 众包配送单追加小费
     * https://developer.waimai.meituan.com/home/docDetail/157
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function additionalTip(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'tip_amount' => 'required|numeric',
        ]);

        return $this->get('order/zhongbao/update/tip', $params);
    }
}
