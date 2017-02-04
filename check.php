<?php
/**
 * GET 请求
 * @param string $url
 */
function http_get($url){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_TIMEOUT, 3 );

    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    if(intval($aStatus["http_code"])==200){
        return $sContent;
    }else{
        return false;
    }
}

/**
 * POST 请求
 * @param string $url
 * @param array $param
 * @param boolean $post_file 是否文件上传
 * @return string content
 */
function http_post($url,$param,$post_file=false){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (is_string($param) || $post_file) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        // foreach($param as $key=>$val){
        // 	$aPOST[] = $key."=".urlencode($val);
        // }
        // $strPOST =  join("&", $aPOST);

        $strPOST = http_build_query($param);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_POST,true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);

    curl_close($oCurl);
    if(intval($aStatus["http_code"])==200){
        return $sContent;
    }else{
        return false;
    }
}

$googleCheck = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=";
$id_token = $_POST['idToken'];
$url = $googleCheck . $id_token;

//服务器需要翻墙
$res = http_get($url);
var_dump($res);

/*
返回格式：
{
 "iss": "accounts.google.com",
 "at_hash": "pHZfKwFY3dTzskHX8I_TSB",
 "aud": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
 "sub": "102483782479812341234",
 "email_verified": "true",
 "azp": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
 "email": "xxxxx@gmail.com",
 "iat": "1470814118",
 "exp": "1470817718",
 "name": "Jingchang Go",
 "picture": "https://lh5.googleusercontent.com/-d4GQnmA27pk/AAAAAAAAAAI/AAAAAAAAABE/HwFMhBii2Bw/s96-c/photo.jpg",
 "given_name": "Jingchang",
 "family_name": "Go",
 "locale": "zh-CN",
 "alg": "RS123",
 "kid": "6faa4e9ec30030784b8942606fb6176212341234"
}
*/

//TODO:验证是否过期，验证aud是否与client_id一致；sub就是google返回的用户唯一凭证