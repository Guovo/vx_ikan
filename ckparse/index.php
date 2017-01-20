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
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
require_once FCPATH.'config.php';
$url = $_GET['url'];
$vid = $_GET['vid'];
if(isset($url)){$zty = 'url';$addenc = $url;}
elseif(isset($vid)){$zty = 'vid';$addenc = $vid;}
else{exit('缺少必须参数url/vid~!');}
if(!is_referer()) exit('403');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Ckparse云解析-在线播放</title></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>*{margin:0;padding:0}</style>
</head>
<body>
<div id="a1"></div>
<script type="text/javascript" src="ckplayer/ckplayer.js" charset="utf-8"></script>
<script type="text/javascript">
	var isWap = navigator.userAgent.match(/iPad|iPhone|iPod|Android/i) != null;
	if(isWap){
		document.getElementById('a1').innerHTML='<video id="videoId" src="<?php echo UURL?>api.php?<?php echo $zty;?>=<?php echo $addenc;?>&wap=1" width="100%" height="100%" controls="controls" autoplay="autoplay" loop="loop">Your browser does not support the video tag , from ckparse.com</video>';
	}else{
		flashvars={
			f:'<?php echo UURL?>api.php?<?php echo $zty;?>=[$pat]',
			a:'<?php echo $addenc;?>',
			s:2,c:0,p:1
		};		var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
		var video=['->video/mp4'];
		CKobject.embed('ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','100%',false,flashvars,video,params);
	}
function playerstop(){
window.parent.frames.MacPlayer.GoNextUrl();
return false;
};
</script>
</body>