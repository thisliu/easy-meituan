<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Illuminate\Validation\Rule;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Logistic extends Client
{
    public const STATUS_PENDING = 0;
    public const STATUS_CONFIRMED = 10;
    public const STATUS_ARRIVED_STORE = 15;
    public const STATUS_PICKED_UP_MEALS = 20;
    public const STATUS_DELIVERED = 40;
    public const STATUS_CANCELED = 100;

    public const STATUS_LABELS = [
        self::STATUS_PENDING => '配送单发往配送',
        self::STATUS_CONFIRMED => '配送单已确认',
        self::STATUS_ARRIVED_STORE => '骑手已到店',
        self::STATUS_PICKED_UP_MEALS => '骑手已取餐',
        self::STATUS_DELIVERED => '骑手已送达',
        self::STATUS_CANCELED => '配送单已取消',
    ];

    /**
     * 下发美团配送订单
     * https://developer.waimai.meituan.com/home/docDetail/136
     */
    public function push(int $orderId): ResponseInterface
    {
        return $this->get('order/logistics/push', ['order_id' => $orderId]);
    }

    /**
     * 取消美团配送订单
     * https://developer.waimai.meituan.com/home/docDetail/139
     */
    public function cancel(int $orderId): ResponseInterface
    {
        return $this->get('order/logistics/cancel', ['order_id' => $orderId]);
    }

    /**
     * 获取配送订单状态
     * https://developer.waimai.meituan.com/home/docDetail/142
     */
    public function status(int $orderId): ResponseInterface
    {
        return $this->get('order/logistics/status', ['order_id' => $orderId]);
    }

    /**
     * 专快混配送转为商家自配送
     * https://developer.waimai.meituan.com/home/docDetail/236
     */
    public function change2PoiSelf(int $orderId): ResponseInterface
    {
        return $this->post('order/logistics/change/poi_self', ['order_id' => $orderId]);
    }

    /**
     * 自配订单同步配送信息
     * https://developer.waimai.meituan.com/home/docDetail/325
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function riderPosition(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'logistics_status' => [
                'required',
                Rule::in(\array_keys(self::STATUS_LABELS))
            ],
            'courier_name' => 'required|string',
            'courier_phone' => 'required|string',
            'third_logistics_id' => 'required|integer',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'back_flow_time' => 'required|integer',
        ]);

        return $this->post('order/riderPosition', $params);
    }

    /**
     * 拉取骑手真实手机号（必接）
     * https://developer.waimai.meituan.com/home/docDetail/385
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getRiderInfoPhoneNumber(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'integer',
            'offset' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        return $this->post('order/getRiderInfoPhoneNumber', $params);
    }

    /**
     * 获取取消跑腿配送原因列表
     * https://developer.waimai.meituan.com/home/docDetail/432
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getCancelDeliveryReason(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'app_poi_code' => 'required|integer',
        ]);

        return $this->post('order/getCancelDeliveryReason', $params);
    }

    /**
     * 取消跑腿配送
     * https://developer.waimai.meituan.com/home/docDetail/433
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function cancelLogisticsByWmOrderId(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'reason_code' => 'required|string',
            'detail_content' => 'required|string',
            'app_poi_code' => 'required|string',
        ]);

        return $this->post('order/cancelLogisticsByWmOrderId', $params);
    }

    /**
     * 三方配送-查询已绑定和可绑定门店配送商和服务包
     * https://developer.waimai.meituan.com/home/docDetail/446
     */
    public function getDistributorList(string $appPoiCode): ResponseInterface
    {
        return $this->post('order/thirdLogistics/getDistributorList', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 三方配送-预发配送
     * https://developer.waimai.meituan.com/home/docDetail/447
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function prePushThirdLogistics(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'app_poi_code' => 'required|string',
            'distributor_code' => 'required|string',
            'service_package_code' => 'required|string',
        ]);

        return $this->post('order/thirdLogistics/prePushThirdLogistics', $params);
    }

    /**
     * 三方配送-发起配送
     * https://developer.waimai.meituan.com/home/docDetail/448
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function pushThirdLogistics(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'data' => 'required|json',
        ]);

        return $this->post('order/thirdLogistics/pushThirdLogistics', $params);
    }

    /**
     * 三方配送-添加小费
     * https://developer.waimai.meituan.com/home/docDetail/449
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function addTipAmount(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'app_poi_code' => 'required|string',
            'distributor_code' => 'required|string',
            'service_package_code' => 'required|string',
            'tip_amount' => 'required|integer',
        ]);

        return $this->post('order/thirdLogistics/addTipAmount', $params);
    }

    /**
     * 三方配送-获取取消配送原因列表
     * https://developer.waimai.meituan.com/home/docDetail/450
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getCancelReasonList(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'app_poi_code' => 'required|string',
        ]);

        return $this->post('order/thirdLogistics/getCancelReasonList', $params);
    }

    /**
     * 三方配送-取消配送
     * https://developer.waimai.meituan.com/home/docDetail/451
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function cancelThirdLogistics(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'order_id' => 'required|integer',
            'app_poi_code' => 'required|string',
            'cancel_code' => 'required|integer',
            'cancel_reason' => 'required|string',
            'cancel_other_reason' => 'string',
        ]);

        return $this->post('order/thirdLogistics/cancelThirdLogistics', $params);
    }

    /**
     * 三方配送-查询三方配送发配送服务商
     * https://developer.waimai.meituan.com/home/docDetail/452
     */
    public function getThirdLogisticsPushList(string $appPoiCode): ResponseInterface
    {
        return $this->post('order/thirdLogistics/getThirdLogisticsPushList', ['app_poi_code' => $appPoiCode]);
    }
}
