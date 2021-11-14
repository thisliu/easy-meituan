<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DeliveryRange extends Client
{
    // 混合配送
    public const MIXED_DELIVERY = 'mixed-delivery';
    // 自配送
    public const SELF_DELIVERY = 'self-delivery';

    /**
     * 查询门店配送范围
     * https://developer.waimai.meituan.com/home/docDetail/43
     */
    public function list(string $appPoiCodes): ResponseInterface
    {
        return $this->get('shipping/list', ['app_poi_code' => $appPoiCodes]);
    }

    /**
     * 创建门店配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/40
     *
     * 创建特殊时段配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/266
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function create(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_shipping_code' => 'required|string',
            'type' => 'required|string',
            'area' => 'required|json',
            'min_price' => 'required|numeric',
            'shipping_fee' => 'numeric',
            'time_range' => 'string',
        ]);

        return $this->post(\data_get($params, 'time_range') ? 'shipping/spec/save' : 'shipping/save', $params);
    }

    /**
     * 更新门店配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/40
     *
     * 更新特殊时段配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/266
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function update(array $params): ResponseInterface
    {
        return $this->create($params);
    }

    /**
     * 批量创建配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/46
     *
     * 批量创建门店配送范围（企客专用）
     * https://developer.waimai.meituan.com/home/docDetail/519
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchCreate(array $params, string $type = self::SELF_DELIVERY): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'shipping_data' => 'required|json',
        ]);

        return $this->post((self::SELF_DELIVERY === $type) ? 'shipping/batchsave' : 'shipping/corporate/batchsave', $params);
    }

    /**
     * 批量更新配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/46
     *
     * 批量更新门店配送范围（企客专用）
     * https://developer.waimai.meituan.com/home/docDetail/519
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchUpdate(array $params, string $type = self::SELF_DELIVERY): ResponseInterface
    {
        return $this->batchCreate($params, $type);
    }

    /**
     * 查询门店配送范围（混合送）
     * https://developer.waimai.meituan.com/home/docDetail/49
     *
     * 查询门店配送范围（企客专用）
     * https://developer.waimai.meituan.com/home/docDetail/520
     */
    public function show(string $appPoiCodes, string $type = self::SELF_DELIVERY): ResponseInterface
    {
        return $this->get((self::SELF_DELIVERY === $type) ? 'shipping/fetch' : 'shipping/corporate/list', ['app_poi_code' => $appPoiCodes]);
    }

    /**
     * 删除门店配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/263
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function destroy(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_shipping_code' => 'required|string',
        ]);

        return $this->post('shipping/delete', $params);
    }

    /**
     * 重置门店配送范围（自配）
     * https://developer.waimai.meituan.com/home/docDetail/443
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function reset(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_shipping_code' => 'required|string',
            'type' => 'required|string',
            'area' => 'required|json',
            'min_price' => 'required|numeric',
            'shipping_fee' => 'numeric',
        ]);

        return $this->post('shipping/resetSelfDeliveryArea', $params);
    }
}
