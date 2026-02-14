## wenshuai/tools

一个简单实用的 PHP 工具库，目前提供：

- **`StringTool`**: 生成随机验证码 / 随机字符串（支持大写、小写、长度、自定义数量等）
- **`HttpRequest`**: 封装的 GET / POST 请求（支持自定义 Header、超时时间、状态码校验）

> 代码命名空间为 `wenshuai\tools`，Composer 自动加载前缀为 `Wenshuai\Tools\`，请按你项目中实际使用的命名空间来 `use`。

---

### 安装

- **通过 Composer 安装**

```bash
composer require wenshuai/tools
```

如果是本地开发包/私有仓库，请在你项目的 `composer.json` 中配置仓库源，然后再执行 `composer require`。

---

### 自动加载

`composer.json` 中已经配置了 PSR-4 自动加载：

```json
{
  "autoload": {
    "psr-4": {
      "Wenshuai\\Tools\\": "src/"
    }
  }
}
```

在你的项目中引入 Composer 自动加载：

```php
require __DIR__ . '/vendor/autoload.php';
```

然后即可使用工具类（根据你实际使用的命名空间调整）：

```php
use Wenshuai\Tools\StringTool;
use Wenshuai\Tools\HttpRequest;
```

---

### StringTool 使用示例

- **生成单个随机字符串**

```php
use Wenshuai\Tools\StringTool;

// 生成 4 位，仅数字 + 大写字母
$code = StringTool::generateCode(4);

// 生成 6 位，包含数字 + 大写 + 小写
$codeWithLower = StringTool::generateCode(6, true);
```

参数说明：

- **`$length`**: 随机字符串长度，默认为 `4`
- **`$includeLowercase`**: 是否包含小写字母，默认为 `false`

- **生成多个不重复随机字符串**

```php
use Wenshuai\Tools\StringTool;

// 生成 10 个不重复的 6 位随机码，包含大小写
$codes = StringTool::generateMultipleCodes(10, 6, true);

foreach ($codes as $item) {
    echo $item . PHP_EOL;
}
```

参数说明：

- **`$count`**: 需要生成的数量
- **`$length`**: 每个随机字符串长度，默认为 `4`
- **`$includeLowercase`**: 是否包含小写字母，默认为 `false`

返回值：

- 返回一个数组，每一项为一个随机字符串。

---

### HttpRequest 使用示例

`HttpRequest` 封装了简单的 GET / POST 请求，底层使用 `curl`。

- **GET 请求**

```php
use Wenshuai\Tools\HttpRequest;

$url = 'https://api.example.com/user';
$params = [
    'id' => 1,
];

$response = HttpRequest::get($url, $params);

echo $response;
```

- **POST 请求**

```php
use Wenshuai\Tools\HttpRequest;

$url = 'https://api.example.com/login';
$data = [
    'username' => 'test',
    'password' => '123456',
];

$response = HttpRequest::post($url, $data);

echo $response;
```

- **自定义 Header / 超时时间 / 校验 HTTP 状态码**

```php
use Wenshuai\Tools\HttpRequest;

$url = 'https://api.example.com/data';
$data = ['foo' => 'bar'];
$header = [
    'Content-Type: application/json',
    'Accept: application/json',
];

// 超时时间 10 秒，期望 HTTP 状态码为 200
$response = HttpRequest::post($url, json_encode($data), $header, 10, 200);

echo $response;
```

方法签名：

```php
public static function get($url, $data = [], $header = [], $timeOut = 15, $httpCode = null)
public static function post($url, $data = [], $header = [], $timeOut = 15, $httpCode = null)
```

参数说明：

- **`$url`**: 请求地址
- **`$data`**: 请求参数
  - GET 时会自动拼接到 URL 上
  - POST 时直接作为 `CURLOPT_POSTFIELDS` 发送
- **`$header`**: 自定义请求头数组，不传则使用内置的浏览器 UA
- **`$timeOut`**: 超时时间（秒），默认 `15`
- **`$httpCode`**: 期望的 HTTP 状态码（可选）
  - 传入具体状态码（如 `200`）时，如果不匹配，将返回错误信息字符串
  - 不传则直接返回请求结果内容

返回值：

- 默认返回接口响应内容（字符串）
- 如果设置了 `$httpCode` 且响应码不符合预期，则返回一段错误描述字符串

---

### 环境要求

- **PHP 版本**: 建议 PHP 7.0 及以上（`StringTool` 使用了 `random_bytes`）
- **扩展依赖**:
  - `curl` 扩展（用于 `HttpRequest`）

---

### 许可证

本项目基于 `LICENSE` 文件中所述的开源许可证发布。