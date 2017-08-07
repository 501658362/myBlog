<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mockery\CountValidator\Exception;

/**
 * @brief HttpRequest请求类
 * @Class HttpRequest
 * @package Common\Util
 */
class HttpRequest {

    /**
     * 结果 HttpResponse
     * @var null
     */
    private $_response = null;
    private $responseType;
    private $_headers = null;
    private $_optionType = 0;
    const OPTION_TYPE_JSON = 'json';
    const OPTION_TYPE_FORM = 'form_params';

    /**
     * @brief 构造函数
     * @brief HttpRequest constructor.
     * @param array $headers
     * @param HttpResponseType $responseType
     */
    public function __construct(Array $headers = [], $responseType = HttpResponseType::UBRESPONSE) {
        if (!isset( HttpResponseType::$values[ $responseType ] )) {
            throw new Exception('不合法的参数');
        }
        //初始化第三方请求类
        $this->client = new Client();
        $this->response = new HttpResponse();
        $this->_optionType = HttpRequestParamType::OPTION_TYPE_JSON;
        $this->_headers = $headers;
        $this->responseType = $responseType;
    }

    /**
     * @brief 获取用户ip
     * @param boolean $useInt 是否将ip转为int型，默认为true
     * @param boolean $returnAll 如果有多个ip时，是否会部返回。默认情况下为false
     * @return string|array|false
     */
    public static function getIp($useInt = false, $returnAll = false) {
        //        $ip = getenv('HTTP_CLIENT_IP');
        //        if($ip && strcasecmp($ip, "unknown") && !preg_match("/192\.168\.\d+\.\d+/", $ip)) {
        //            return self::_returnIp($ip, $useInt, $returnAll);
        //        }
        // 获取remote_addr_ip
        $remoteAddrIp = false;
        $ip = getenv('REMOTE_ADDR');
        if ($ip && strcasecmp($ip, "unknown")) {
            $remoteAddrIp = $ip;
        } elseif (isset( $_SERVER[ 'REMOTE_ADDR' ] )) {
            $ip = $_SERVER[ 'REMOTE_ADDR' ];
            if ($ip && strcasecmp($ip, "unknown")) {
                $remoteAddrIp = $ip;
            }
        }
        // 不是内网私有
        //        if ($remoteAddrIp && !self::_isPrivateIp($remoteAddrIp)) {
        //            return self::_returnIp($remoteAddrIp, $useInt, $returnAll);
        //        }
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        if ($ip && strcasecmp($ip, "unknown")) {
            return self::_returnIp($ip, $useInt, $returnAll);
        }
        //存在 remote_addr_ip
        if ($remoteAddrIp) {
            return self::_returnIp($remoteAddrIp, $useInt, $returnAll);
        }
        return false;
    }

    /**
     * 赋Header值
     * @param array $headers
     */
    public function setHeaders(Array $headers = []) {
        $this->_headers = $headers;
    }

    public function setHeaderAccessToken($access_token) {
        if (empty( $access_token ) || strlen(trim($access_token)) == 0) {
            return false;
        }
        if (empty( $this->_headers ) || count($this->_headers) == 0) {
            $this->_headers = array ();
        }
        //dd($this->_headers[] = ['3'=>1]);
        $this->_headers[ 'Authorization' ] = sprintf('Bearer %s', trim($access_token));
        return true;
    }

    /**
     * @brief GET 请求
     * @param $url 请求地址
     * @param array $options 参数
     * @return HttpResponse 结果集
     * @throws \Exception
     */
    public function get($url, Array $options = [], $paramType = 'json') {
        try {
            $args = $this->_initArguments($options, $paramType);
            $res = $this->client->get($url, $args);
            $this->_response($res);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $this->_response;
    }

    /**
     * @brief POST 请求
     * @param $url 请求地址
     * @param array $options 参数
     * @return HttpResponse 结果集
     * @throws \Exception
     */
    public function post($url, Array $options = [], $paramType = 'json') {
        try {
            $args = $this->_initArguments($options, $paramType);
            $res = $this->client->post($url, $args);
            $this->_response($res);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $this->_response;
    }

    /**
     * @brief PUT 请求
     * @param $url 请求地址
     * @param array $options 参数
     * @return HttpResponse 结果集
     * @throws \Exception
     */
    public function put($url, Array $options = [], $paramType = 'json') {
        try {
            $args = $this->_initArguments($options, $paramType);
            $res = $this->client->put($url, $args);
            $this->_response($res);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $this->_response;
    }

    /**
     * @brief DELETE 请求
     * @param $url 请求地址
     * @param array $options 参数
     * @return HttpResponse 结果集
     * @throws \Exception
     */
    public function delete($url, Array $options = [], $paramType = 'json') {
        try {
            $args = $this->_initArguments($options, $paramType);
            $res = $this->client->delete($url, $args);
            $this->_response($res);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $this->_response;
    }

    public function postCustom($url, $parms, $files = null, $fileName = null) {
        $eol = "\r\n";
        $data = '';
        $mime_boundary = md5(time());
        if (!empty( $parms ) && is_array($parms)) {
            // 配置参数
            foreach ($parms as $key => $value) {
                $data .= '--' . $mime_boundary . $eol;
                $data .= 'Content-Disposition: form-data; ';
                $data .= "name=" . $key . $eol . $eol;
                $data .= $value . $eol;
            }
        }
        //        if (empty( $fileName )) {
        //            $fileName = basename($files);
        //        }
        if (!empty( $files )) {
            $files = explode(';', $files);
            $fileNames = explode(';', $fileName);
            foreach ($files as $i => $file) {
                if (!empty( $file ) && file_exists($file)) {
                    $name = '';
                    if (isset( $fileNames[ $i ] )) {
                        $name = $fileNames[ $i ];
                    } else {
                        $name = $fileNames[ 0 ];
                    }
                    $handle = fopen($file, 'rb');
                    $content = fread($handle, filesize($file));
                    // 配置文件
                    $data .= '--' . $mime_boundary . $eol;
                    $data .= sprintf('Content-Disposition: form-data; name="somefile"; filename="%s"', $name) . $eol;
                    $data .= 'Content-Type: text/plain' . $eol;
                    $data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
                    $data .= $content . $eol;
                }
            }
        }
        $data .= "--" . $mime_boundary . "--" . $eol . $eol;
        $request = new Request('POST', $url, ['Content-Type' => 'multipart/form-data;boundary=' . $mime_boundary], $data);
        $res = $this->client->send($request);
        $this->_response($res);
        return $this->_response;
    }

    public function authorization($client_id, $client_secret) {
    }

    /**
     * @brief 初始化请求参数
     * @param array $arguments
     * @return array
     */
    private function _initArguments(Array $arguments, $paramType) {
        if (!empty( $paramType ) && $this->_optionType != $paramType) {
            $paramType = new HttpRequestParamType($paramType);
            $this->_optionType = $paramType->getParamType();
        }
        $args = ['verify' => false];
        if (!empty( $arguments ) && count($arguments) > 0) $args = [$this->_optionType => $arguments];
        if (!empty( $this->_headers ) && count($this->_headers) > 0) $args = array_merge($args, ['headers' => $this->_headers]);
        return $args;
    }

    /**
     * @brief 初始化结果集
     * @param $res response对象
     */
    private function _response($res) {
        $this->_response = $this->response->initResponse($res, $this->responseType);
    }

    /**
     * @brief 格式化IP
     * @param $ip
     * @param $useInt
     * @param $returnAll
     * @return array|bool
     */
    private static function _returnIp($ip, $useInt, $returnAll) {
        if (!$ip) return false;
        $ips = preg_split("/[，, _]+/", $ip);
        if (!$returnAll) {
            $ip = $ips[ count($ips) - 1 ];
            return $useInt ? self::ip2long($ip) : $ip;
        }
        $ret = array ();
        foreach ($ips as $ip) {
            $ret[] = $useInt ? self::ip2long($ip) : $ip;
        }
        return $ret;
    }

    public static function ip2long($ip) {
        return sprintf('%u', ip2long($ip));
    }
}

class HttpRequestParamType extends PHPEnum {

    const OPTION_TYPE_JSON      = 'json';
    const OPTION_TYPE_FORM      = 'form_params';
    const OPTION_TYPE_FORM_FILE = 'multipart';
    private $paramType = null;

    public function __construct($type) {
        parent::__construct($type);
        $this->paramType = $type;
        return $this;
    }

    public function getParamType() {
        return $this->paramType;
    }
}

/**
 * @brief HttpResponse结果集
 * @Class HttpResponse
 * @package Common\Util
 */
class HttpResponse {

    private $_headers;
    private $_paginate;
    private $_statusCode;
    private $_result;
    private $_data;
    private $_code;
    private $_message;
    private $_body;
    private $_content;

    #region Public Methods
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * @brief 获取头文件信息
     * @return mixed
     */
    public function getHeader() {
        return $this->_headers;
    }

    /**
     * @brief 获取头文件信息
     * @return Array paginate
     */
    public function getPaginate() {
        return $this->_paginate;
    }

    /**
     * @brief 获取结果码
     * @return Int code
     */
    public function getCode() {
        return $this->_code;
    }

    /**
     * @brief 获取消息
     * @return String Message
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * @brief 获取数据信息
     * @return mixed
     */
    public function getData() {
        return $this->_data;
    }

    public function getBody() {
        return $this->_body;
    }

    public function getContent() {
        return $this->_content;
    }

    public function getResult() {
        return $this->_result;
    }

    /**
     * @brief 初始化HttpRequest结果集
     * @param $response
     * @param HttpResponseType $responseType
     * @return $this|null
     * @internal param $toJson
     */
    public function initResponse($response, $responseType) {
        if ($response != null) {
            $this->_statusCode = $response->getStatusCode();
            $this->_headers = $response->getHeaders();
            $this->_body = $response->getBody();
            $this->_content = $this->_body->getContents();
            if (empty( $this->_content )) {
                return null;
            }
            if ($responseType === HttpResponseType::CUSTOM) {
                return $this;
            }
            $this->_result = json_decode($this->_content, true);
            $this->_data = array_get($this->_result, 'data');
            $this->_code = array_get($this->_result, 'code');
            $this->_message = array_get($this->_result, 'message');
            if ($response->hasHeader('Paginate') && isset( $response->getHeader('Paginate')[ 0 ] )) {
                $this->_paginate = json_decode($response->getHeader('Paginate')[ 0 ], true);
            }
        }
        return $this;
    }
    #endregion Public Methods
}