﻿
var npcycss = '<style>@media only screen and (max-width:500px){body .MacPlayer{width:100%!important;height:260px;}}</style>';
MacPlayer.Html = '<iframe width="100%" height="100%" src="http://jxapi.nepian.com/ckparse/?url='+MacPlayer.PlayUrl+'" frameborder="0" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>'+npcycss+'';
MacPlayer.Show();