<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Interfaces\ResponseInterface;
use EasyMeiTuan\Support\Validator;
use Illuminate\Validation\Rule;

class Store extends Client
{
    public const DELIVERABLE_BUSINESS_STATUS = 1;
    public const RESTING_BUSINESS_STATUS = 3;

    // 营业状态
    public const BUSINESS_STATUS_LABELS = [
        self::DELIVERABLE_BUSINESS_STATUS => '可配送',
        self::RESTING_BUSINESS_STATUS => '休息中',
    ];

    /**
     * 获取门店ID
     * https://developer.waimai.meituan.com/home/docDetail/4
     *
     * 批量获取门店详细信息
     * https://developer.waimai.meituan.com/home/docDetail/7
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function list(array $params = []): ResponseInterface
    {
        Validator::verify($params, [
            'only_id' => 'boolean',
            'app_poi_codes' => [
                'array',
                Rule::requiredIf(!$onlyId = (\data_get($params, 'only_id', false))),
            ],
        ]);
        
        if (!$onlyId) {
            $params['app_poi_codes'] = join(',', $params['app_poi_codes']);
        }

        return $this->get($onlyId ? 'poi/getids' : 'poi/mget', $params);
    }

    /**
     * 创建门店信息
     * https://developer.waimai.meituan.com/home/docDetail/1
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function create(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'name' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'pic_url' => 'string',
            'pic_url_large' => 'string',
            'phone' => 'required|string',
            'standby_tel' => 'string',
            'shipping_fee' => 'required|numeric',
            'shipping_time' => 'required|string',
            'promotion_info' => 'required|string',
            'open_level' => [
                'required',
                Rule::in(\array_keys(self::BUSINESS_STATUS_LABELS))
            ],
            'is_online' => 'required|in:0,1',
            'invoice_support' => 'integer',
            'invoice_min_price' => 'integer',
            'invoice_description' => 'string',
            'third_tag_name' => 'required|string',
            'pre_book' => 'integer',
            'time_select' => 'integer',
            'app_brand_code' => 'string',
        ]);

        return $this->post('poi/save', $params);
    }

    /**
     * 更新门店信息
     * https://developer.waimai.meituan.com/home/docDetail/1
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function update(array $params): ResponseInterface
    {
        return $this->create($params);
    }

    /**
     * 获取门店详细信息
     * https://developer.waimai.meituan.com/home/docDetail/7
     */
    public function show(string $appPoiCode): ResponseInterface
    {
        return $this->get('poi/mget', ['app_poi_codes' => $appPoiCode]);
    }

    /**
     * 门店设置为休息状态
     * https://developer.waimai.meituan.com/home/docDetail/13
     */
    public function markAsClose(string $appPoiCode): ResponseInterface
    {
        return $this->post('poi/close', ['app_poi_codes' => $appPoiCode]);
    }

    /**
     * 门店设置为上线状态
     * https://developer.waimai.meituan.com/home/docDetail/19
     */
    public function markAsOnline(string $appPoiCode): ResponseInterface
    {
        return $this->post('poi/online', ['app_poi_codes' => $appPoiCode]);
    }

    /**
     * 门店设置为营业状态
     * https://developer.waimai.meituan.com/home/docDetail/347
     */
    public function markAsOpen(string $appPoiCode): ResponseInterface
    {
        return $this->post('poi/open', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 门店设置为下线状态
     * https://developer.waimai.meituan.com/home/docDetail/348
     */
    public function markAsOffline(string $appPoiCode): ResponseInterface
    {
        return $this->post('poi/offline', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 获取门店品类列表
     * https://developer.waimai.meituan.com/home/docDetail/28
     */
    public function categories(): ResponseInterface
    {
        return $this->post('poiTag/list');
    }

    /**
     * 更改门店公告信息
     * https://developer.waimai.meituan.com/home/docDetail/25
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updatePromotionInfo(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'promotion_info' => 'string',
        ]);

        return $this->post('poi/updatepromoteinfo', $params);
    }

    /**
     * 更新门店营业时间
     * https://developer.waimai.meituan.com/home/docDetail/31
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateShippingTime(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'shipping_time' => 'required|string',
        ]);

        return $this->post('shippingtime/update', $params);
    }

    /**
     * 查询门店是否延迟发配送
     * https://developer.waimai.meituan.com/home/docDetail/34
     */
    public function isDelayPush(string $appPoiCode): ResponseInterface
    {
        return $this->post('logistics/isDelayPush');
    }

    /**
     * 设置门店延迟发配送时间
     * https://developer.waimai.meituan.com/home/docDetail/37
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function setDelayPush(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'delay_seconds' => 'required|integer',
        ]);

        return $this->post('logistics/setDelayPush');
    }

    /**
     * 门店是否可开启加权
     * https://developer.waimai.meituan.com/home/docDetail/284
     */
    public function canOpen(string $appPoiCode): ResponseInterface
    {
        return $this->get('weight/canOpen', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 门店开启加权
     * https://developer.waimai.meituan.com/home/docDetail/285
     */
    public function open(string $appPoiCode): ResponseInterface
    {
        return $this->post('weight/open', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 获取门店日账单
     * https://developer.waimai.meituan.com/home/docDetail/326
     *
     * 根据交易类型查询交易列表
     * https://developer.waimai.meituan.com/home/docDetail/588
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function bills(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'start_date' => 'required|integer',
            'end_date' => 'required|integer',
            'offset' => 'required|integer',
            'limit' => 'required|integer',
            'partner_type' => [
                'integer',
                Rule::requiredIf($hasPartnerType = \data_get($params, 'partner_type', false))
            ],
            'bill_charge_types' => [
                'integer',
                Rule::requiredIf($hasChargeType = \data_get($params, 'bill_charge_types', false))
            ],
        ]);

        if ($hasPartnerType && $hasChargeType) {
            return $this->get('wm/bill/getOrderBill', $params);
        }

        return $this->get('wm/bill/list', $params);
    }
}
