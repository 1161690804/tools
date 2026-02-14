<?php
namespace Wenshuai\Tools;
class HttpRequest
{

    private static  $iniHeader = ["user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36"];

    /**
     * Notes: POST
     */
    public static function post($url, $data = [], $header = [], $timeOut = 15, $httpCode = null)
    {
        return self::request($url, $data, 'POST', $httpCode, $header, $timeOut);
    }

    /**
     * Notes: GET
     */
    public static function get($url, $data = [], $header = [], $timeOut = 15, $httpCode = null)
    {
        return self::request($url, $data, 'GET', $httpCode, $header, $timeOut);
    }

    private static function  request($url, $data, $method, $httpCode, $header, $timeOut)
    {
        $curl = curl_init();

        /** 设置请求方式 **/
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        /** POST请求方式传参 **/
        if(!empty($data)) {
            if($method == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }else {
                $url = $url . '?' . http_build_query($data);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        /** 超时时间 **/
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);

        /** header头 **/
        if(empty($header)) $header = self::$iniHeader;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        /** 出现 HTTP 状态码大于或等于 400 的错误时是否停止，目前这里是不停止 **/
        curl_setopt($curl, CURLOPT_FAILONERROR, false);

        /** 设置执行后的结果作为函数的返回值返回，而不是直接输出 **/
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        /** HTTP响应头是否包含在输出中 这里设置的是否 **/
        curl_setopt($curl, CURLOPT_HEADER, 0);

        /** 设置客户端能够接受的压缩编码类型 **/
        curl_setopt($curl, CURLOPT_ACCEPT_ENCODING, "gzip,deflate");

        /** http请求可不验证https证书 **/
        if(stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        }

        /** 请求 */
        $content = curl_exec($curl);
        /** 获取请求信息 */
        $info = curl_getinfo($curl);
        /** 关闭请求资源 */
        curl_close($curl);


        /** 验证网络请求状态 */
        if($httpCode != null){
            if($info['http_code'] === 0) {
                return '['. $method. ']：REQUEST IS ERROR; url:'. $url;
            }elseif(intval($info["http_code"]) != $httpCode) {
                return '['. $method. ']：REQUEST RESULT IS ERROR; url:'. $url. ' , code : '. $info['http_code']. ', content : '. $content;
            }
        }
        return $content;
    }

}
