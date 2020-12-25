# simpleRequest
简单、正经、好用的请求类
https://github.com/Saopanda/simpleRequest

## 实例化

```    
use saopanda\client;

$client = client::new();
//  or
$client = client::new([         
    'timeout'=>'10',
    'VERIFYHOST'=>true,
    'VERIFYPEER'=>true
]);
```

## GET 请求
$client->get(`网址``[,请求参数数组]``[,Headers数组]`)

```
$res = $client->get('qq.com');

$res = $client->get('qq.com',['a'=>1]);  // www.baidu.com?a=1
```   
    
## POST 请求
$client->post(`网址``[,请求参数数组]``[,Headers数组]`)
```
$res = $client->post('qq.com');

$res = $client->post('qq.com',[],[
        'Authorization: Bearer eyJ0eXAiOiJKV'
    ]);
```

## 内容返回
成功

> result 为业务返回，errcode 为0

```
[
    "result"  => '业务返回内容',
    "errmsg"  => "",
    "errcode" => 0
]
```

失败
> result 为false

```
[
    "result"  => false,
    "errmsg"  => "Could not resolve host: qqq.com",
    "errcode" => 6
]
```

## 配置选项
在实例化之后，get()、post() 方法之前 使用如下方法设置

### 设置 POST数据：urlEncoded
```
$client->urlEncodedData([...]);
```

### 设置 POST数据：formData
```
$client->formData([...]);
```

### 设置 POST数据：json
```
$client->jsonData([...]);
```
### 设置 POST数据：raw
```
$client->rawData('...');
```

### 设置 timeout
实例化时指定，或
```
$client->timeout(1);
```

### 设置 headers
优先级比 get()、post() 方法内设置 header低，会被替换
```
$client->headers([
    'Authorization: Bearer eyJ0eXAiOiJKV'
]);
```

### 设置 params
优先级比 get()、post() 方法内设置 params低，会被替换
```
$client->params([
    'code'  =>  '081mN10w3FI4yV2QuM2w3b9Npx1mN10u'
])
```
### 设置证书
使用绝对路径
```
$client->pem('xxx.pem');
```

### 设置证书密钥
使用绝对路径
```
$client->pem('xxx.key');
```

## 链式调用

```
$res = $client->timeout(1)
    ->header($headers)
    ->params($params)
    ->get($url);
```
``` 
$res = $client->headers($headers)
    ->formData($params)
    ->post($url);
```
