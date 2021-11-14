<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Refund extends Client
{
    /**
     * 订单确认退款请求（必接）
     * https://developer.waimai.meituan.com/home/docDetail/121
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function agree(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason' => 'required|string',
        ]);

        return $this->get('order/refund/agree', $params);
    }

    /**
     * 驳回订单退款申请（必接）
     * https://developer.waimai.meituan.com/home/docDetail/124
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function reject(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason' => 'required|string',
        ]);

        return $this->get('order/refund/reject', $params);
    }

    /**
     * 查询部分退款菜品详情
     * https://developer.waimai.meituan.com/home/docDetail/160
     */
    public function getPartRefundFoods(int $orderId): ResponseInterface
    {
        return $this->get('order/getPartRefundFoods', ['order_id' => $orderId]);
    }

    /**
     * 发起部分退款申请
     * https://developer.waimai.meituan.com/home/docDetail/163
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function applyPartRefund(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason' => 'required|string',
            'food_data' => 'required|json',
        ]);

        return $this->post('order/applyPartRefund', $params);
    }
}
