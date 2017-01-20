var randoms = {
	ads_codes: ['document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23648\'><\\/script>");','document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23649\'><\\/script>");','document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23650\'><\\/script>");','document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23651\'><\\/script>");','document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23652\'><\\/script>");','document.writeln("<script src=\'http:\\/\\/slb.gedawang.com\\/s.php?id=23653\'><\\/script>");'],
	ads_weight: [10,10,10,10,10,10],

	get_random: function(weight) {
		var s = eval(weight.join('+'));
		var r = Math.floor(Math.random() * s);
		var w = 0;
		var n = weight.length - 1;
		for(var k in weight){w+=weight[k];if(w>=r){n=k;break;}};
		return n;
	},
	init: function() {

		var rand = randoms.get_random(randoms.ads_weight);
		document.write(randoms.ads_codes[rand]);

	}
}
randoms.init();