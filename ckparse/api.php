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
 */
error_reporting(0);
//文件名称
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// 网站根目录
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
//加载配置文件
require_once FCPATH.'config.php';
//判断防盗链
if(!is_referer()){
	 header('HTTP/1.1 403 Forbidden');
     exit('403');
}
//接收参数
$url = $_GET['url'];
$vid = $_GET['vid'];
$wap = $_GET['wap'] ? $_GET['wap'] : $wap;
if(!isset($_GET['url']) && !isset($_GET['vid'])){
	exit('缺少必须参数url/vid~!');
}
$ckurvipj = $url ? 'url=' . $url : 'vid=' . $vid;
$hd = empty($_GET['hd']) ? VODHD : $_GET['hd'];
//组装参数
$param = $ckurvipj.'&hd='.$hd.'&wap='.$wap;
switch ($wap) {
	case 1:
		$apiurl = get_url(APIURL.'?uid='.UID.'&token='.TOKEN.'&'.$param);
		header('Content-Type:application/force-download');
		header('Location:'.$apiurl);
		break;
	default:
		header('Content-type:text/xml;charset=utf-8');
		$apiurl = get_url(APIURL.'?uid='.UID.'&token='.TOKEN.'&'.$param);
		$apiurl = str_replace('f->?url=', 'f->' . UURL . 'api.php?url=', $apiurl);
		echo $apiurl;
		break;
}