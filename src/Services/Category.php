<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Category extends Client
{
    /**
     * 查询所有类目
     * https://developer.waimai.meituan.com/home/docDetail/403
     */
    public function all(): ResponseInterface
    {
        return $this->get('foodDna/getCategory');
    }

    /**
     * 查询门店菜品分类列表
     * https://developer.waimai.meituan.com/home/docDetail/60
     */
    public function list(string $appPoiCode): ResponseInterface
    {
        return $this->get('foodCat/list', ['app_poi_code' => $appPoiCode]);
    }

    /**
     * 创建菜品分类
     * https://developer.waimai.meituan.com/home/docDetail/52
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function create(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'category_name' => 'required|string',
            'sequence' => 'required|integer',
            'category_description' => 'string',
            'category_mode' => 'integer',
            'top_flag' => 'integer',
            'time_zone' => 'json',
        ]);

        return $this->post('foodCat/update', $params);
    }

    /**
     * 更新菜品分类
     * https://developer.waimai.meituan.com/home/docDetail/52
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function update(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'category_name_origin' => 'required|string',
            'category_name' => 'required|string',
            'sequence' => 'integer',
            'category_description' => 'string',
            'category_mode' => 'integer',
            'top_flag' => 'integer',
            'time_zone' => 'json',
        ]);

        return $this->post('foodCat/update', $params);
    }

    /**
     * 删除菜品分类
     * https://developer.waimai.meituan.com/home/docDetail/53
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function destroy(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'category_name' => 'required|string',
        ]);

        return $this->post('foodCat/delete', $params);
    }

    /**
     * 根据类目查询模板下所有属性
     * https://developer.waimai.meituan.com/home/docDetail/404
     */
    public function getProperties(string $categoryId): ResponseInterface
    {
        return $this->post('foodDna/getPropertiesByCategoryId', ['category_id' => $categoryId]);
    }
}
