## simpleClient
https://github.com/Shiliang0352/simpleClient

https://saopanda.top/default/simpleClient.html

> 前不久本来是想写一个微信服务的轮子，结果最近用了一下，代码写的太烂了。花了俩天时间重写了一遍，顺带连接器一块重写了吧

正如名字，使用简单，逻辑不复杂，封装了一下而已，出点错误也好找。比不起 `guzzlehttp` 。文档看的头疼

### 使用
引入类文件或者自己添加命名空间 use
    
    require_once '...';

实例化使用
    
    $client = Clinet::new([
        ‘timeout’=>'10'    //    内部参数可省略 超时10秒
    ]);
    $client->get();

也可以直接使用
    
    $rs = Clinet::new()->get();
    $rs = Clinet::new(['timeout'=>'10'])->post();

### GET 请求
#### 三个参数 `网址` `请求参数（数组格式，可选）` `请求头（可选）`

> 这里会把`网址`和`请求参数`拼接成一个最终地址来进行请求
    
    $url = 'baidu.com';
    $params = [
        'a'=>1,
        'b'=>2,
        'c'=>2
    ];
    $headers = [
        'Authorization: Bearer eyJ0eXAiOiJKV'
    ];

#### 发起请求
    
    $rs = $client->get($url,$params,$headers);

> 其他
    
    // 简单请求
    $rs = $client->get($url);
     // 带header不带参数
    $rs = $client->get($url,[],$headers); 

#### 以上操作构建的请求如下
    
    GET /?a=1&b=2&c=2 HTTP/1.1
    Host:baidu.com
    Authorization: Bearer eyJ0eXAiOiJKV
    
    

### POST 请求
#### 三个参数 `网址`  `数据` `请求参数（数组格式，可选）`

> `网址`和`请求参数`同 `GET`。这俩项最后会拼接成一个 url，`abc.com?a=1&b=2`

#### 发送 `x-www-form-urlencoded` 格式
    
    $data['urlEncoded'] = [
        'aa'=>'aa',
        'bb'=>'bb',
        'cc'=>'cc'
    ];


    POST / HTTP/1.1
    Host: xxx.com
    Content-Type: application/x-www-form-urlencoded

    aa=aa&bb=bb&cc=cc

#### 发送 `form-data`。可以放参数，也可以直接放文件路径上传文件
    
    $data['data'] = [
        'aa'=>'aa',
        'bb'=>'bb',
        'cc'=>'./666.jpg'
    ];


    POST / HTTP/1.1
    Host: xxx.com
    Content-Type: multipart/form-data;

    (data) 

#### 发送 `json` 。 可以直接发中文json不会乱码
    
    $data['json'] = [
        'aa'=>'aa',
        'bb'=>'bb'
    ];


    POST / HTTP/1.1
    Host: xxx.com
    Content-Type: application/json;

    (json字符串)


#### 发送 `raw`
    
    $data['raw'] = '神秘文字';


    POST / HTTP/1.1
    Host: xxx.com
    Content-Type: text/plain;

    (神秘文字)

#### 自定义header
    
    $data['headers'] = [
        'Authorization: Bearer eyJ0eXAiOiJKV'
    ];

#### 携带证书
    
    $data['pem'] = './xxx.pem';
    $data['pem_key'] = './xxx.pem';

#### 发起请求
    
    $rs = $client->post($url,$data,$params);

### 响应
![WechatIMG194 1.png][3]

响应会直接返回，全局携带3个属性，`data` `E_msg` `E_code`

* data  
响应内容，当为false时请求失败，请用 E_code 判断状态
* E_msg 
错误信息
* E_code 
为 0 时请求成功


  [3]: https://saopanda.top/usr/uploads/2020/03/432555039.png
