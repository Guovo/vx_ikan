<?php
/**
 * CKParse云解析插件
 * 
 * 官方网站    www.ckparse.com
 * QQ群        107028575(1群)，577200423(2群)
 * @author     朝阳<515233307@qq.com>
 * @version    2.0
 * @since      1.0
 *
 *
 * 以下为用户修改区，请按照说明修改
 * @REFERER_URL 防盗链域名，多个用|隔开，如：http://www.123.com|http://www.abc.com
				务必带上http:// 关闭请留空，测试时不要填写防盗链域名
 * @UID	    	用户授权UID
 * @TOKEN 	    用户授权token
 * @VODHD 		视频默认清晰度，1标清，2高清，3超清，4原画，如果没有高清会自动下降一级【建议尽量设置在1和2，不然会影响速度】
 * @UURL 	    您网站的api.php地址，勿改，除已改ckparse文件名
 */
define('REFERER_URL','http://v.z-boss.cn/|http://tv.z-boss.cn/|http://vip.z-boss.cn/');
define('UID','80000000000');
define('TOKEN','3afab3130a2779003d039d6a37240a37');
define('VODHD','2');
define('UURL',http_url().'/ckparse/');
error_reporting(0);
//*******修改区域结束********
date_default_timezone_set("Asia/Shanghai");
header('Content-type:text/html;charset=utf-8');
define('APIURL', get_api());
$wap = preg_match("/(iPhone|iPad|iPod|Linux|Android)/i", strtoupper($_SERVER['HTTP_USER_AGENT']));
function http_url(){
	$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	return $http_type.$_SERVER['HTTP_HOST'];
}
function get_api(){
    return get_key('0vKdjZR0Pk3aRSgRq/_Fu_ZYIQxqKl4Iv2sYdyqbI339JkgYTputMGyr7GyTsS2rBn2hrPGpEg','D','ckparse.com');
}
function is_referer(){
	global $wap;
    if(REFERER_URL=='') return true;
	if(empty($_SERVER['HTTP_REFERER']) && $wap==1){
		return true;
	}else{
        $ext = explode("|",REFERER_URL);
        for($i=0;$i<count($ext);$i++){
		    if(strpos(strtolower($_SERVER['HTTP_REFERER']),strtolower($ext[$i])) !== FALSE){
               return true; 
            }
		}
	}
    return false;
}
function get_url($url) {
	$url = $url.'&ref='.rawurlencode($_SERVER['HTTP_REFERER']);
    if (!function_exists('curl_init') || !function_exists('curl_exec')) {
        exit('您的主机不支持Curl，请开启~');
	}
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'CkParse');
    curl_setopt($curl, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
function get_key($string,$operation='E',$key=''){
	if($operation=='E') $string.='-time-'.(time()+1800);
	if($key=='') $key=md5(TOKEN);
	$key_length=strlen($key);
	$string=$operation=='D'?base64_decode(str_replace('-','/',str_replace('_','+',$string))):substr(md5($string.$key),0,8).$string;
	$string_length=strlen($string);
	$rndkey=$box=array();
	$result='';
	for($i=0;$i<=255;$i++){
		$rndkey[$i]=ord($key[$i%$key_length]);
		$box[$i]=$i;
	}
	for($j=$i=0;$i<256;$i++){
		$j=($j+$box[$i]+$rndkey[$i])%256;
		$tmp=$box[$i];
		$box[$i]=$box[$j];
		$box[$j]=$tmp;
	}
	for($a=$j=$i=0;$i<$string_length;$i++){
		$a=($a+1)%256;
		$j=($j+$box[$a])%256;
		$tmp=$box[$a];
		$box[$a]=$box[$j];
		$box[$j]=$tmp;
		$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
	}
	if($operation=='D'){
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
				$str = substr($result,8);
				$arr = explode("-time-",$str);
				if(strpos($arr[0],'.ckparse.') === FALSE && (empty($arr[1]) || $arr[1]<time())) return '';
				return $arr[0];
			}else{
				return '';
			}
	}else{
		return str_replace('+','_',str_replace('=','',base64_encode($result)));
	}
}