<h1 align="center"> finecho/meituan </h1>

<p align="center"> 美团开放平台SDK.</p>
官方文档：https://developer.waimai.meituan.com/home/doc/food/1

## 安装

环境要求：

- PHP >= 8.0

```shell
$ composer require finecho/meituan -vvv
```

## 配置
```php
$config = [
    // 必填，app_id、secret_id
    'app_id' => 10020201024, 
    'secret_id' => 'AKIDsiQzQla780mQxLLU2GJCxxxxxxxxxxx', 
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
        'body' => ['name' => 'finecho 的快餐店', 'address' => '深圳市南山区']
    ]
);

// 也可以直接这样
$response = $app->store->create(['name' => 'finecho 的快餐店', 'address' => '深圳市南山区']);
```
### 方式二 - 原始方式调用
```php
use EasyMeiTuan\Application;

$app = new Application($config);

$api = $app->getClient();

$response = $api->post(
    '/poi/save',
    [
        'body' => ['name' => 'finecho 的快餐店', 'address' => '深圳市南山区']
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
        'body' => ['name' => 'finecho 的快餐店', 'address' => '深圳市南山区']
    ]
);
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

## Usage

TODO

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/finecho/meituan/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/finecho/meituan/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
