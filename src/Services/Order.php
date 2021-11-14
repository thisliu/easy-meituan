<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Order extends Client
{
    /**
     * 设订单为商家已收到
     * https://developer.waimai.meituan.com/home/docDetail/106
     */
    public function receive(int $orderId): ResponseInterface
    {
        return $this->get('order/poi_received', ['order_id' => $orderId]);
    }

    /**
     * 商家确认订单（必接）
     * https://developer.waimai.meituan.com/home/docDetail/109
     */
    public function confirm(int $orderId): ResponseInterface
    {
        return $this->get('order/confirm', ['order_id' => $orderId]);
    }

    /**
     * 商家取消订单（必接）
     * https://developer.waimai.meituan.com/home/docDetail/112
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function cancel(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason' => 'required|string',
            'reason_code' => 'required|integer',
        ]);

        return $this->get('order/cancel', $params);
    }

    /**
     * 订单配送中
     * https://developer.waimai.meituan.com/home/docDetail/115
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function delivering(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'courier_name' => 'string',
            'courier_phone' => 'string',
        ]);

        return $this->get('order/delivering', $params);
    }

    /**
     * 订单已送达
     * https://developer.waimai.meituan.com/home/docDetail/118
     */
    public function arrived(int $orderId): ResponseInterface
    {
        return $this->get('order/arrived', ['order_id' => $orderId]);
    }

    /**
     * 查询订单状态
     * https://developer.waimai.meituan.com/home/docDetail/127
     */
    public function status(int $orderId): ResponseInterface
    {
        return $this->get('order/viewstatus', ['order_id' => $orderId]);
    }

    /**
     * 查询活动信息
     * https://developer.waimai.meituan.com/home/docDetail/130
     */
    public function getActDetailByAcId(int $actDetailId): ResponseInterface
    {
        return $this->get('order/getActDetailByAcId', ['act_detail_id' => $actDetailId]);
    }

    /**
     * 获取订单详细信息
     * https://developer.waimai.meituan.com/home/docDetail/133
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function show(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'is_mt_logistics' => 'integer',
        ]);

        return $this->get('order/getOrderDetail', $params);
    }

    /**
     * 获取最新日订单流水号
     * https://developer.waimai.meituan.com/home/docDetail/145
     */
    public function getOrderDaySeq(string $appPoiCode): ResponseInterface
    {
        return $this->get('order/getOrderDaySeq', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 根据流水号获取订单ID
     * https://developer.waimai.meituan.com/home/docDetail/148
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getOrderIdByDaySeq(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'date_time' => 'required|integer',
            'day_seq' => 'required|integer',
        ]);

        return $this->get('order/getOrderIdByDaySeq', $params);
    }

    /**
     * 催单回复接口
     * https://developer.waimai.meituan.com/home/docDetail/166
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function remindReply(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'remind_id' => 'required|integer',
            'reply_id' => 'required|integer',
            'reply_content' => 'required|string',
        ]);

        return $this->post('order/remindReply', $params);
    }

    /**
     * 商家确认已完成出餐
     * https://developer.waimai.meituan.com/home/docDetail/215
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function preparationMealComplete(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'cabinetCode' => 'string',
        ]);

        return $this->get('order/cabinetCode', $params);
    }

    /**
     * 商家获取备餐时间
     * https://developer.waimai.meituan.com/home/docDetail/218
     */
    public function getPreparationMealTime(int $orderId): ResponseInterface
    {
        return $this->get('order/getPreparationMealTime', ['order_id' => $orderId]);
    }

    /**
     * 拉取用户真实手机号（必接）
     * https://developer.waimai.meituan.com/home/docDetail/221
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchPullPhoneNumber(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'offset' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        return $this->post('order/batchPullPhoneNumber', $params);
    }

    /**
     * 查询可申请餐损赔付的订单
     * https://developer.waimai.meituan.com/home/docDetail/224
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getSupportedCompensation(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'offset' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        return $this->get('order/getSupportedCompensation', $params);
    }

    /**
     * 申请餐损赔付
     * https://developer.waimai.meituan.com/home/docDetail/227
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function applyCompensation(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason' => 'required|string',
            'apply_status' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        return $this->post('order/getSupportedCompensation', $params);
    }

    /**
     * 查询餐损赔付结果
     * https://developer.waimai.meituan.com/home/docDetail/230
     */
    public function getCompensationResult(int $orderId): ResponseInterface
    {
        return $this->get('order/getCompensationResult', ['order_id' => $orderId]);
    }

    /**
     * 批量拉取异常订单
     * https://developer.waimai.meituan.com/home/docDetail/252
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchFetchAbnormalOrder(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'start_time' => 'required|numeric',
            'end_time' => 'required|numeric',
            'type' => 'required|numeric',
            'offset' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        return $this->post('order/batchFetchAbnormalOrder', $params);
    }

    /**
     * 批量查询客服赔付商家责任订单信息
     * https://developer.waimai.meituan.com/home/docDetail/358
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchCompensationOrder(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'orderViewIds' => 'required|json',
            'app_poi_code' => 'required|string',
        ]);

        return $this->get('order/batchCompensationOrder', $params);
    }

    /**
     * 上报卡餐
     * https://developer.waimai.meituan.com/home/docDetail/566
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function reportKc(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'kc_scene_type' => 'required|integer',
            'kc_report_type' => 'required|integer',
            'kc_report_time' => 'required|integer',
            'kc_extend_fct' => 'integer',
        ]);

        return $this->get('order/common/kc/report', $params);
    }
}
