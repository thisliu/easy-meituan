<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Product extends Client
{
    /**
     * 查询门店菜品列表
     * https://developer.waimai.meituan.com/home/docDetail/57
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function list(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'offset' => 'integer',
            'limit' => 'integer',
        ]);

        return $this->get('food/list', $params);
    }

    /**
     * 创建菜品（新版）
     * https://developer.waimai.meituan.com/home/docDetail/55
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function create(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
            'name' => 'required|string|max:30',
            'description' => 'string|max:255',
            'spuAttr' => 'json',
            'skus' => 'json',
            'price' => 'required|numeric',
            'min_order_count' => 'required|integer',
            'unit' => 'required|string',
            'box_num' => 'required|integer',
            'box_price' => 'required|numeric',
            'category_name' => 'required|string',
            'is_sold_out' => 'required|integer',
            'picture' => 'string',
            'sequence' => 'integer',
            'pictures' => 'string',
            'speciality' => 'integer',
            'is_not_single' => 'integer',
        ]);

        return $this->post('food/save', $params);
    }

    /**
     * 更新菜品（新版）
     * https://developer.waimai.meituan.com/home/docDetail/55
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function update(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
            'name' => 'string|max:30',
            'description' => 'string|max:255',
            'price' => 'numeric',
            'min_order_count' => 'integer',
            'unit' => 'string',
            'box_num' => 'integer',
            'box_price' => 'numeric',
            'category_name' => 'string',
            'is_sold_out' => 'integer',
            'picture' => 'string',
            'sequence' => 'integer',
        ]);

        return $this->post('food/save', $params);
    }

    /**
     * 删除菜品
     * https://developer.waimai.meituan.com/home/docDetail/56
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function destroy(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
        ]);

        return $this->post('food/delete', $params);
    }

    /**
     * 批量创建菜品（新版）
     * https://developer.waimai.meituan.com/home/docDetail/59
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchCreate(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'sku_overwrite' => 'boolean',
            'food_data' => 'required|json',
        ]);

        return $this->post('food/batchinitdata', $params);
    }

    /**
     * 批量更新菜品（新版）
     * https://developer.waimai.meituan.com/home/docDetail/59
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchUpdate(array $params): ResponseInterface
    {
        return $this->batchCreate($params);
    }

    /**
     * 创建 SKU 信息
     * https://developer.waimai.meituan.com/home/docDetail/61
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function createSku(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
            'skus' => 'json',
        ]);

        return $this->post('food/sku/save', $params);
    }

    /**
     * 更新 SKU 信息
     * https://developer.waimai.meituan.com/home/docDetail/61
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateSku(array $params): ResponseInterface
    {
        return $this->createSku($params);
    }

    /**
     * 删除 SKU 信息
     * https://developer.waimai.meituan.com/home/docDetail/62
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function destroySku(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
            'sku_id' => 'required|string',
        ]);

        return $this->post('food/sku/delete', $params);
    }

    /**
     * 更新 SKU 价格
     * https://developer.waimai.meituan.com/home/docDetail/63
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateSkuPrice(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
        ]);

        return $this->post('food/sku/price', $params);
    }

    /**
     * 更新 SKU 库存
     * https://developer.waimai.meituan.com/home/docDetail/64
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateSkuStock(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
        ]);

        return $this->post('food/sku/stock', $params);
    }

    /**
     * 增加 SKU 库存
     * https://developer.waimai.meituan.com/home/docDetail/65
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function addSkuStock(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
        ]);

        return $this->post('food/sku/inc_stock', $params);
    }

    /**
     * 减少 SKU 库存
     * https://developer.waimai.meituan.com/home/docDetail/66
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function reduceSkuStock(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
        ]);

        return $this->post('food/sku/desc_stock', $params);
    }

    /**
     * 绑定菜品属性
     * https://developer.waimai.meituan.com/home/docDetail/67
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function bindProperties(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_property' => 'required|json',
        ]);

        return $this->post('food/bind/property', $params);
    }

    /**
     * 菜品属性列表
     * https://developer.waimai.meituan.com/home/docDetail/68
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function properties(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
        ]);

        return $this->get('food/property/list', $params);
    }

    /**
     * 查询菜品详情
     * https://developer.waimai.meituan.com/home/docDetail/69
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function show(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
        ]);

        return $this->get('food/get', $params);
    }

    /**
     * 批量更新售卖状态
     * https://developer.waimai.meituan.com/home/docDetail/70
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchUpdateSellStatus(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
            'sell_status' => 'required|integer',
        ]);

        return $this->get('food/sku/sellStatus', $params);
    }

    /**
     * 根据原商品编码更换新商品编码
     * https://developer.waimai.meituan.com/home/docDetail/294
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateAppFoodCodeByOrigin(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'app_food_code_origin' => 'required|string|max:128',
            'app_food_code' => 'required|string|max:128',
            'sku_id_origin' => 'string',
            'sku_id' => 'string',
        ]);

        return $this->get('food/updateAppFoodCodeByOrigin', $params);
    }

    /**
     * 根据商品名称和规格名称更换新的商品编码
     * https://developer.waimai.meituan.com/home/docDetail/295
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function updateAppFoodCodeByNameAndSpec(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'name' => 'required|string',
            'category_name' => 'required|string',
            'spec' => 'string',
            'app_food_code' => 'required|string|max:128',
            'sku_id' => 'string',
        ]);

        return $this->get('food/updateAppFoodCodeByNameAndSpec', $params);
    }

    /**
     * 批量创建或更新菜品（同步逻辑）
     * 如果菜品原来不存在，本次存在就新增；如果菜品原来存在，本次存在就更新；如果菜品原来存在，本次不存在就删除
     * https://developer.waimai.meituan.com/home/docDetail/301
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchSync(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'food_data' => 'required|json',
        ]);

        return $this->get('food/batchbulksave', $params);
    }

    /**
     * 查询商品DNA
     * https://developer.waimai.meituan.com/home/docDetail/405
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function getTemplateBySpuId(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'app_food_code' => 'required|string',
        ]);

        return $this->post('foodDna/getTemplateBySpuId', $params);
    }

    /**
     * 保存商品DNA
     * https://developer.waimai.meituan.com/home/docDetail/406
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function saveFoodDna(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'food_data' => 'required|json',
        ]);

        return $this->post('foodDna/saveFoodDna', $params);
    }

    /**
     * 批量查询商品DNA
     * https://developer.waimai.meituan.com/home/docDetail/674
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchQueryFoodDna(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'app_food_codes' => 'required|string',
        ]);

        return $this->post('foodDna/batchQueryFoodDna', $params);
    }

    /**
     * 批量删除商品
     * https://developer.waimai.meituan.com/home/docDetail/690
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function batchDelSpu(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string',
            'app_food_codes' => 'required|string',
        ]);

        return $this->post('food/batchDelSpu', $params);
    }
}
