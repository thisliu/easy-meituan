<h1 align="center"> finecho/meituan </h1>

<p align="center"> 美团开放平台 SDK</p>

## 温馨提示
⚠️  目前仅支持美团外卖服务

## 安装

环境要求：

- PHP >= 8.0

```shell
composer require finecho/meituan -vvv
```

## 配置

```php
$config = [
    // 必填，app_id、secret_id
    'app_id' => 10020201024, 
    'secret_id' => 'AKIDsiQzQla780mQxLLU2GJCxxxxxxxxxxx', 
    
    // 是否开启表单验证
    'form_verify' => false,
];
```

## 使用

您可以使用三种调用方式：封装方式调用、原始方式调用 和 链式调用，请根据你的喜好自行选择使用方式，效果一致。

### 方式一 - 封装方式调用

```php
use EasyMeiTuan\Application;

$app = new Application($config);

$response = $app->store->create(
    [
        'name'    => 'finecho 的快餐店',
        'address' => '深圳市南山区',
    ]
);

// 也可以这样
$response = $app->store->create(
    [
        'body' => [
            'name'    => 'finecho 的快餐店',
            'address' => '深圳市南山区',
        ],
        'headers' => [],
    ]
);
```

### 方式二 - 原始方式调用

```php
use EasyMeiTuan\Application;

$app = new Application($config);

$api = $app->getClient();

$response = $api->post(
    '/poi/save',
    [
        'name'    => 'finecho 的快餐店',
        'address' => '深圳市南山区',
    ]
);
```

### 方式三 - 链式调用

你可以将需要调用的 API 以 / 分割 + 驼峰写法的形式，写成如下模式：

```php
use EasyMeiTuan\Application;

$app = new Application($config);

$api = $app->getClient();

$response = $api->poi->save->post(
    [
        'name'    => 'finecho 的快餐店',
        'address' => '深圳市南山区',
    ]
);
```

## 表单校验

如果开启表单校验，如果参数缺失或者异常，则会抛出 [InvalidParamsException](https://github.com/finecho/easy-meituan/blob/main/src/Exceptions/InvalidParamsException.php) 异常

## 美团推送

在接收美团推送的时候，`Server` 会对签名进行校验，并返回解码后的内容

```php
$server = $app->getServer();

// url：在美团外卖设置的回调地址
// content：美团外卖推送过来的内容, 在美团外卖开放平台配置回调地址美团服务器发起验证码时 content 为空数组
$server->withUrl($url)->with(
    function ($content) {
        // ...
    }
);

return $server->serve();
```

签名校验的时候, 需要将已编码的字段内容进行解码，SDK 提供属性可自行配置 decode 规则。
- url：对值进行 `urldecode`
- json：为对值进行 `json_decode(val, true)`
```php
// 默认需要解码字段以及规则
\EasyMeiTuan\Server::$casts = [
    'caution' => 'url',
    'detail' => 'url|json',
    'extras' => 'url|json',
    'recipient_name' => 'url',
    'wm_poi_address' => 'url',
    'recipient_address' => 'url',
    'incmp_modules' => 'url|json',
    'order_tag_list' => 'url|json',
    'backup_recipient_phone' => 'url|json',
    'recipient_address_desensitization' => 'url',

    // FBI Warning: nested content needs to pay attention to the order!
    'poi_receive_detail_yuan' => 'url|json',
    'poi_receive_detail_yuan.reconciliationExtras' => 'json',
    'poi_receive_detail' => 'url|json',
    'poi_receive_detail.reconciliationExtras' => 'json',
];
```

## API

API 接口众多，每一个 API 都会注释上美团文档地址，查询困难时，可以直接搜索匹配。

### :globe_with_meridians: 门店

[美团门店文档](https://developer.waimai.meituan.com/home/doc/food/1)

具体方法：[src/Services/Store.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Store.php)

```php
$app->store->$method();
```

### :truck: 配送范围

[美团配送文档](https://developer.waimai.meituan.com/home/doc/food/2)

具体方法：[src/Services/DeliveryRange.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/DeliveryRange.php)

```php
$app->deliveryRange->$method();
```

### :memo: 类目

[美团类目文档](https://developer.waimai.meituan.com/home/doc/food/3)

具体方法：[src/Services/Category.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Category.php)

```php
$app->category->$method();
```

### :beers: 菜品

[美团菜品文档](https://developer.waimai.meituan.com/home/doc/food/3)

具体方法：[src/Services/Product.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Product.php)

```php
$app->product->$method();
```

### :page_facing_up: 订单

[美团订单文档](https://developer.waimai.meituan.com/home/doc/food/6)

具体方法：[src/Services/Order.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Order.php)

```php
$app->order->$method();
```

#### :wastebasket: 订单退款

具体方法：[src/Services/Refund.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Refund.php)

```php
$app->refund->$method();
```

#### :truck: 订单配送

具体方法：[src/Services/Logistic.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Logistic.php)

```php
$app->logistic->$method();
```

#### :package: 众包

具体方法：[src/Services/CrowdSourcing.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/CrowdSourcing.php)

```php
$app->crowdSourcing->$method();
```

### :wrench: 全局公共

具体方法：[src/Services/Product.php](https://github.com/finecho/easy-meituan/blob/main/src/Services/Product.php)

```php
$app->common->$method();
```

### 返回值

API Client 基于 [symfony/http-client](https://symfony.com/doc/current/http_client.html) 实现，你可以通过以下方式对响应值进行访问：

```php
// 获取状态码
$statusCode = $response->getStatusCode();
// 获取全部响应头
$headers = $response->getHeaders();
// 获取响应原始内容
$content = $response->getContent();
// 获取 json 转换后的数组格式
$content = $response->toArray();
// 将内容转换成 Stream 返回
$content = $response->toStream();
// 获取其他信息，如："response_headers", "redirect_count", "start_time", "redirect_url" 等.
$httpInfo = $response->getInfo();
// 获取指定信息
$startTime = $response->getInfo('start_time');
// 获取请求日志
$httpLogs = $response->getInfo('debug');                                             
```

在原有 Response 的基础上，增加了以下几种方法：

```php
// 请求是否正常
$isSuccess = $response->isSuccess(): bool;
// 请求是否出现异常
$hasError = $response->hasError(): bool;
// 获取错误内容（code + msg）
$error = $response->getError(): array;
// 获取错误信息
$error = $response->getErrorMsg(): ?string;
// 获取错误码
$error = $response->getErrorCode(): string|int|null;
// 获取正常返回的数据
$data = $response->getData(): mixed;

```

## 一个比较完整的示例

```php
require __DIR__ .'/vendor/autoload.php';

use EasyMeiTuan\Application;
use EasyMeiTuan\Exceptions\InvalidParamsException;

$config = [
    'app_id' => 'xxx',
    'secret_id' => 'xxxxxxxxxxx',
    'form_verify' => true,
];

$app = new Application($config);

try {
    $response = $app->store->list();

    if ($response->hasError()) {
        $error = $response->getError();

        // .....
    }

    $data = $response->getData();

    // ....

} catch (InvalidParamsException $e) {
    // 捕获到表单异常
}
```

## License

MIT
