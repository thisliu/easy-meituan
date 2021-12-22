<?php

namespace EasyMeiTuan\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ParamsDecoder
{
    public static array $casts = [
        'caution' => 'url',
        'detail' => 'url|json',
        'extras' => 'url|json',
        'wm_poi_name' => 'url',
        'wm_poi_address' => 'url',
        'wm_poi_phone' => 'url',
        'recipient_address' => 'url',
        'incmp_modules' => 'url|json',
        'order_tag_list' => 'url|json',
        'recipient_name' => 'url',
        'backup_recipient_phone' => 'url|json',
        'recipient_address_desensitization' => 'url',
        'food' => 'json',
        'invoice_title' => 'url',
        
        // FBI Warning: nested content needs to pay attention to the order!
        'poi_receive_detail_yuan' => 'url|json',
        'poi_receive_detail_yuan.reconciliationExtras' => 'json',
        'poi_receive_detail' => 'url|json',
        'poi_receive_detail.reconciliationExtras' => 'json',
    ];

    public function formatContentToValidator(array $content): array
    {
        foreach ($content as $key => $value) {
            $rule = static::$casts[$key] ?? false;

            if ($rule && Str::startsWith($rule, 'url')) {
                $content[$key] = urldecode($value);
            }
        }

        return $content;
    }

    public function formatContentToApp(array $content): array
    {
        foreach (static::$casts as $key => $rule) {
            $value = Arr::get($content, $key);

            if (is_string($value)) {
                $rules = explode('|', $rule);

                foreach ($rules as $method) {
                    $method = 'decode' . ucfirst($method);
                    $value = static::{$method}($value);
                }

                Arr::set($content, $key, $value);
            }
        }

        return $content;
    }

    protected static function decodeUrl(string $value): string
    {
        return urldecode($value);
    }

    protected static function decodeJson(string $value): array
    {
        return json_decode($value, true);
    }
}
