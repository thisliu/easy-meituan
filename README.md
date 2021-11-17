<h1 align="center"> finecho/meituan </h1>

<p align="center"> :fire::fire:  火热开发中  :fire::fire:</p>

<p align="center"> 美团开放平台SDK</p>
官方文档：https://developer.waimai.meituan.com/home/doc/food/1

## 警告
⚠️  SDK 还在开发阶段，大部分 API 未经过真实测试，慎重使用哦

## 安装

环境要求：

- PHP >= 8.0

```shell
$ composer require finecho/meituan:dev-main -vvv
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
        'name' => 'finecho 的快餐店', 'address' => '深圳市南山区'
    ]
);

// 也可以这样
$response = $app->store->create(
    [
        'body' => ['name' => 'finecho 的快餐店', 'address' => '深圳市南山区'],
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
        'name' => 'finecho 的快餐店', 'address' => '深圳市南山区'
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
        'name' => 'finecho 的快餐店', 'address' => '深圳市南山区'
    ]
);
```

## 表单校验
如果开启表单校验，如果参数缺失或者异常，则会抛出 [InvalidParamsException](https://github.com/finecho/easy-meituan/blob/main/src/Exceptions/InvalidParamsException.php) 异常

## 美团推送
在接收美团推送的时候，需要对签名进行校验
```php
$app->verifySignature(string: "当前路由地址", array: "推送过来的参数");
```

## API
API 接口众多，每一个 API 都会注释上美团文档地址，查询困难时，可以直接搜索匹配。

### :globe_with_meridians: 门店
文档地址：https://developer.waimai.meituan.com/home/doc/food/1

具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Store.php
```php
$app->store->$method();
```

### :truck: 配送范围
文档地址：https://developer.waimai.meituan.com/home/doc/food/2

具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/DeliveryRange.php
```php
$app->deliveryRange->$method();
```

### :memo: 类目
文档地址：https://developer.waimai.meituan.com/home/doc/food/3

具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Category.php
```php
$app->category->$method();
```

### :beers: 菜品
文档地址：https://developer.waimai.meituan.com/home/doc/food/3

具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Product.php
```php
$app->product->$method();
```

### :page_facing_up: 订单
文档地址：https://developer.waimai.meituan.com/home/doc/food/6

具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Order.php
```php
$app->order->$method();
```
#### :wastebasket: 订单退款
具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Refund.php
```php
$app->refund->$method();
```
#### :truck: 订单配送
具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Logistic.php
```php
$app->logistic->$method();
```
#### :package: 众包
具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/CrowdSourcing.php
```php
$app->crowdSourcing->$method();
```


### :wrench: 全局公共
具体方法：https://github.com/finecho/easy-meituan/blob/main/src/Services/Product.php
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
$isError = $response->isError(): bool;
// 获取错误内容（code + msg）
$error = $response->getError(): array;
// 获取错误信息
$error = $response->getErrorMsg(): ?string;
// 获取错误码
$error = $response->getErrorCode(): string|int|null;
// 获取正常返回的数据
$data = $response->getData(): array;

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

    if ($response->isError()) {
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
