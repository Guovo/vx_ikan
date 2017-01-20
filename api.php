<?php
/**
  * wechat php test
  */

//define your token
//weixinabc是一个token,是一个令牌
define("TOKEN", "880707");
$wechatObj = new wechatCallbackapiTest();

//$wechatObj->responseMsg();
$wechatObj->valid();
//exit;

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];


        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }


    public function responseMsg()
    {

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


		if (!empty($postStr)){

                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);

				$event = $postObj->Event;			
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";    
				


				switch($postObj->MsgType)
				{
					case 'event':

						if($event == 'subscribe')
						{
						//关注后的回复
												$contentStr = "感谢您谢你关注《花园影视》!\r\n请直接回复想看的电视剧或电影!\r\n电视剧排行请回复1\r\n电影排行请回复2\r\n 最新美剧请回复3\r\n美女写真请回复4\r\n激情伦理回复5\r\n（你懂的） \r\n本站网址http://v.oxxxxx.cn/\r\n本站资源涵盖爱奇艺、优酷、乐视、搜狐、腾讯等各大影视站全站资源\r\n每天免费获得更多抢先版、激情伦理剧请添加微信免费获取\r\n  <a href="https://mmbiz.qlogo.cn/mmbiz_jpg/O7QjNNTvqghPVKCaD1FRhlib7oChM9J47TN1ficwQJSuq4mkfeqQSzJEmB3bUunibwUd6g4kl50ZGoLUZA2ojuahw/0?wx_fmt=jpeg" >微信号tangtangboss点击添加</a> ";


							$msgType = 'text';
							$textTpl = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $textTpl;

						}
						break;
					case 'text':
						if(preg_match('/[\x{4e00}-\x{9fa5}]+/u',$keyword))
						{	

							$newsTplHeader = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>%s</ArticleCount>
							<Articles>";

							$newsTplItem = "<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>";
							$newsTplFooter="</Articles>
							</xml>";
 
									$con = mysql_connect("127.0.0.1","hyys","sny880707");								
									mysql_query("SET NAMES UTF8");
									mysql_query("set character_set_client=utf8"); 
									mysql_query("set character_set_results=utf8");
									mysql_select_db("hyys", $con);
									$sql = "SELECT * FROM `mac_vod` WHERE `d_name` like '%".$keyword."%'  LIMIT 0 , 10";

									$result = mysql_query($sql);
									$itemCount = 0;
								if(mysql_num_rows($result)>0){
								while($row = mysql_fetch_assoc($result))
								{

									$title = "".$row['d_name']."";
									$des ="";
									$url ="http://v.oxxxxx.cn/?m=vod-detail-id-".$row['d_id'].".html";
									$picUrl1 ="http://v.oxxxxx.cn/".$row['d_pic']."";
									$contentStr .= sprintf($newsTplItem, $title, $des, $picUrl1, $url);																													
									++$itemCount;	
								}							
								$newsTplHeader = sprintf($newsTplHeader, $fromUsername, $toUsername, $time, $itemCount);
								$resultStr =  $newsTplHeader. $contentStr. $newsTplFooter;
								echo $resultStr; 
								}
								else
								{
									$newsTpl = "<xml>
										<ToUserName><![CDATA[%s]]></ToUserName>
										<FromUserName><![CDATA[%s]]></FromUserName>
										<CreateTime>%s</CreateTime>
										<MsgType><![CDATA[news]]></MsgType>
										<ArticleCount>1</ArticleCount>
										<Articles>
										<item>
										<Title><![CDATA[%s]]></Title> 
										<Description><![CDATA[%s]]></Description>
										<PicUrl><![CDATA[%s]]></PicUrl>
										<Url><![CDATA[%s]]></Url>
										</item>							
										</Articles>
										</xml>";						
								
								//没有查找到的时候的回复
										$title = '实在不好意思，您要的影视，我还没有添加';
										
										$des1 ="请联系我的微信，我可以免费帮您添加，我的微信：tangtangboss（长按可复制）";
										
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_jpg/O7QjNNTvqghPVKCaD1FRhlib7oChM9J47TN1ficwQJSuq4mkfeqQSzJEmB3bUunibwUd6g4kl50ZGoLUZA2ojuahw/0?wx_fmt=jpeg";
										
										$url="https://mmbiz.qlogo.cn/mmbiz_jpg/O7QjNNTvqggzIxXxu6gqbMN1pUmIYPU7z227icN2mibr5RPfvxWe2tDWkpen9ozRRGK31MVOQrA4wFklo2QotEag/0?wx_fmt=jpeg";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	

								}
										mysql_close($con);
									
								}																		
						else
						{
							$newsTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>							
							</Articles>
							</xml>";	
 						if($keyword=="1")
						{
										$title = '电视剧排行：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_png/O7QjNNTvqggzIxXxu6gqbMN1pUmIYPU7fW7KtOLIuibH6jnYcibapp1bbU4Pfz9oyNSevtiar0hrL5hTAxYFyy9Sw/0?wx_fmt=png";
										//跳转链接
										$url="http://v.oxxxxx.cn/?m=vod-type-id-2.html";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="2")
						{
										$title = '电影排行：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_png/O7QjNNTvqggzIxXxu6gqbMN1pUmIYPU7SS3M0iahMZUMHgibgKtiaWYXctrJ8PqwWJSyJ1Lh1SXr0Nh34e8MeukDg/0?wx_fmt=png";
										//跳转链接
										$url="http://v.oxxxxx.cn/?m=vod-type-id-1.html";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="3")
						{
										$title = '精彩美剧：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_png/O7QjNNTvqggzIxXxu6gqbMN1pUmIYPU7fW7KtOLIuibH6jnYcibapp1bbU4Pfz9oyNSevtiar0hrL5hTAxYFyy9Sw/0?wx_fmt=png";
										//跳转链接
										$url="http://v.oxxxxx.cn/?m=vod-type-id-15.html";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
						if($keyword=="4")
						{
										$title = '美女写真：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_jpg/3VDmVYnfBWKOUg461w5fUtROGKduJgWucrHib0tj33Sia14vzPbUpvxWA9cISfZHepB2r6RuLTB1yY0vTVfEJ6Dg/0?wx_fmt=jpeg";
										//跳转链接
										$url="http://v.oxxxxx.cn/?m=vod-type-id-38.html";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
                                                if($keyword=="5")
						{
										$title = '激情伦理：点击进入';
										
										$des1 ="";
										//图片地址
										$picUrl1 ="https://mmbiz.qlogo.cn/mmbiz_jpg/3VDmVYnfBWKOUg461w5fUtROGKduJgWucrHib0tj33Sia14vzPbUpvxWA9cISfZHepB2r6RuLTB1yY0vTVfEJ6Dg/0?wx_fmt=jpeg";
										//跳转链接
										$url="http://v.oxxxxx.cn/?m=vod-type-id-36.html";

										$resultStr= sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $des1, $picUrl1, $url) ;
									
										echo $resultStr; 	
						}
												$contentStr = " 不好意思您输入错误 \r\n 请输入电视剧或电影名如：   “灵魂摆渡” \r\n 即可在线观看！ \r\n 电视剧排行请回复1 \r\n 电影排行请回复2 \r\n 最新美剧请回复3 \r\n 美女写真请回复4 \r\n 伦理剧情回复5 \r\n （你懂的）";


							$msgType = 'text';
							$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $resultStr;
						}					
						break;
					default:
						break;
				}						

        }else {
        	echo "你好！欢迎关注《花园影视》微信公众号";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>
        
        
        
        
        
        
        
        
        
        
        