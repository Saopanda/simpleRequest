<?php

namespace saopanda;

/**
 * Class Clinet
 * saopanda
 */
class client{

    private static $instance;
    /**
     * @var int 超时时间 默认15秒
     */
    private $timeout=15;
    private $CURLOPT_SSL_VERIFYHOST=false;
    private $CURLOPT_SSL_VERIFYPEER=false;
    private $headers = [];
    private $params = [];
    private $pem;
    private $pemKey;
    private $postData;

    private function __construct(){}

    public static function new(array $data=array())
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self;

            if (isset($data['timeout'])){
                if (is_numeric($data['timeout']))
                    self::$instance->timeout = $data['timeout'];
            }
            if (isset($data['VERIFYHOST'])){
                if (is_bool($data['VERIFYHOST']))
                    self::$instance->timeout = $data['VERIFYHOST'];
            }
            if (isset($data['VERIFYPEER'])){
                if (is_bool($data['VERIFYPEER']))
                    self::$instance->timeout = $data['VERIFYPEER'];
            }

        }
        return self::$instance;
    }

    /**
     * 设置超时时间
     * @param int $timeout
     * @return $this
     */
    public function timeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * 设置 Headers
     * @param array $headers
     * @return $this
     *
     */
    public function headers(array $headers)
    {
        $this->headers[] = $headers;
        return $this;
    }

    /**
     * 设置 params
     * @param array $params
     * @return $this
     */
    public function params(array $params)
    {
        $this->params[] = $params;
        return $this;
    }

    /**
     * 设置证书
     * @param $pem
     * @return $this
     * @throws \Exception
     */
    public function pem($pem)
    {
        if (is_file($pem)){
            $this->pem = $pem;
            return $this;
        }else{
            throw new \Exception('找不到证书');
        }
    }

    /**
     * 设置证书密钥
     * @param $pemKey
     * @return $this
     * @throws \Exception
     */
    public function pemKey($pemKey)
    {
        if (is_file($pemKey)){
            $this->pemKey = $pemKey;
            return $this;
        }else{
            throw new \Exception('找不到证书密钥');
        }
    }

    /**
     * 发送 GET 请求
     * @param $url
     * @param array $params 网址参数
     * @param array $headers
     * @return mixed
     */
    public function get($url,array $params=[],array $headers=[])
    {
        if (count($params) == 0 ){
            $params = $this->params;
        }
        $url = $this->buildUrl($url,$params);
        if (count($headers) == 0){
            $headers = $this->headers;
        }
        return $this->GET_DO($url,$headers);
    }


    /**
     * 发送 Post 请求
     * @param $url
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    public function post($url,array $params=[],array $headers=[])
    {
        if (count($params) == 0 ){
            $params = $this->params;
        }
        $url = $this->buildUrl($url,$params);
        if (count($headers) == 0){
            $headers = $this->headers;
        }

        return $this->POST_DO($url,$headers);
    }

    /**
     * 设置 POST 数据：urlEncoded
     * @param array $data
     * @return $this
     */
    public function urlEncodedData(array $data)
    {
        $this->postData = trim($this->buildUrl('',$data['urlEncoded']),'?');
        $this->headers[] = 'Content-Type: application/x-www-form-urlencoded';

        return $this;
    }

    /**
     * 设置 POST 数据：formData
     * @param array $data
     * @return $this
     */
    public function formData(array $data)
    {
        foreach ($data as $k =>&$v){
            if(is_file('./'.$v)){
                $v = new \CURLFILE('./'.$v);
            }
        }
        $this->postData = $data;
        $this->headers[] = 'Content-Type: multipart/form-data;';

        return $this;
    }

    /**
     * 设置 POST 数据：json
     * @param array $data
     * @return $this
     */
    public function jsonData(array $data)
    {
        $this->postData = json_encode($data,JSON_UNESCAPED_UNICODE);
        $this->headers[] = 'Content-Type: application/json';

        return $this;
    }

    /**
     * 设置 POST 数据：raw
     * @param string $data
     * @return $this
     */
    public function rawData(string $data)
    {
        $this->postData = $data;

        return $this;
    }

    /**
     * @param $url
     * @param array $headers
     * @return array
     */
    protected function POST_DO($url,array $headers)
    {
        $ch = curl_init();
        if($this->pem){
            //默认格式为PEM， cert
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT,$this->pem);
        }
        if($this->pemKey){
            //默认格式为PEM， key
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY,$this->pemKey);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$this->postData);
        curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
        curl_setopt($ch,CURLOPT_SAFE_UPLOAD,true);  //  禁用@上传文件
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,$this->CURLOPT_SSL_VERIFYHOST);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$this->CURLOPT_SSL_VERIFYPEER);
        $res = [
            'result' => curl_exec($ch),
            'errmsg' => curl_error($ch),
            'errcode' => curl_errno($ch)
        ];
        curl_close($ch);
        return $res;
    }

    /**
     * @param $url
     * @param $headers
     * @return array
     */
    protected function GET_DO($url,array $headers)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->CURLOPT_SSL_VERIFYPEER);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->CURLOPT_SSL_VERIFYHOST);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = [
            'result' => curl_exec($ch),
            'errmsg' => curl_error($ch),
            'errcode' => curl_errno($ch)
        ];
        curl_close($ch);
        return $res;
    }

    /**
     * 构建请求 URL
     * @param $url
     * @param $params
     * @return string
     */
    protected function buildUrl($url,$params)
    {
        $num = strrpos($url,'?');
        if ( $num ){
            $url = substr($url,0,$num).'?';
        }else{
            $url .= '?';
        }
        foreach ($params as $kk => $vv) {
            $url .= $kk .'='.$vv.'&';
        }
        $url = trim($url,'&');
        return $url;
    }
}