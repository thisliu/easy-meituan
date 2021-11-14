<?php

declare(strict_types=1);

namespace EasyMeiTuan\Services;

use EasyMeiTuan\Client;
use EasyMeiTuan\Support\Validator;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Common extends Client
{
    /**
     * 图片上传
     * https://developer.waimai.meituan.com/home/docDetail/209
     *
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public function upload(array $params): ResponseInterface
    {
        Validator::verify($params, [
            'app_poi_code' => 'required|string|max:128',
            'img_data' => 'required|array',
            'img_name' => 'required|string',
        ]);

        return $this->post('image/upload', $params);
    }
}
