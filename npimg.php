<?php
/**
 *『那片』全网在线资源采集平台 - 奇艺图片本地显示插件
 * 官方网站    www.nepian.com
 * QQ群        107028575(1群)，577200423(2群)
 * @author     朝阳<515233307@qq.com>
 * @version    3.0
 * @since      1.0
 * @备注       npimg.php?pic=
 */
header("Content-Type:image/jpeg");
@$picurl = $_GET['pic'];
echo getnepianImg($picurl);
function getnepianImg($url){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$data=curl_exec($ch);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '20');
	return $data;
	curl_close($ch);
}