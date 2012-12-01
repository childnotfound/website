<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<script type="text/javascript"
			src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/handlebars-1.0.rc.1.js"></script>
		<script id="child-tpl" type="text/x-handlebars-template">
<div class="notfound-wrapper">
	<p class="banner">我們找不到這頁，但你有看到<strong> {{name}} </strong>嗎？</p>
<div class="child-wrapper">
	<div class="title">協 尋 焦 點</div>
	<div class="avatar">
		<img src="{{avatar}}" />
		<p class="name"> {{name}} </p>
	</div>
	<div class="info">
		<dl class="name-value">
			<dd>姓名：</dd>		<dt>{{name}}</dt>
			<dd>性別：</dd>		<dt>{{sex}}</dt>
			<dd>現在年齡：</dd>	<dt>{{currentAge}}</dt>
			<dd>失蹤年齡：</dd>	<dt>{{missingAge}}</dt>
			<dd>失蹤日期：</dd>	<dt>{{missingDate}}</dt>
			<dd>特徵：</dd>		<dt>{{character}}</dt>
			<dd>失蹤地區：</dd>	<dt>{{missingRegion}}</dt>
			<dd>失蹤原因：</dd>	<dt>{{missingCause}}</dt>
		</dl>
	</div>
	<div class="contact">
		聯絡資訊、<br /> 協尋資訊
	</div>
	<div class="clearer"></div>
</div>
</div>
		</script>
		<style type="text/css">
div { margin: 0px; padding: 0px; }
.clearer { clear: both; }
div#wrapper { 
	width: 600px; 
	position: absolute; 
	left: 50%; 
	margin-left: -300px;
	margin-top: 30px;
}
dl.name-value { padding: 0px;}
dl.name-value dd { 
	margin: 0px;
	width: 40%; 
	float: left; 
	color: #555; 
	text-align: right;
	clear: left;
}
dl.name-value dt { width: 60%; float: left; }
.notfound-wrapper {
	padding: 6px;
	padding-bottom: 18px;
	border-radius: 5px;
	background-color: #00adee;
	box-shadow:2px 2px 8px #06C;
}
.notfound-wrapper p.banner{ 
	padding: 2px;
	margin: 16px 16px;
	font-size: 24px;
	color: #fff;
}
.child-wrapper { 
	position: relative;
	border-radius: 5px;
	padding: 5px;
	background-color: #8df;
}
.child-wrapper .title { 
	float: left;
	width: 6%;
	padding: 1%;
	font-size: 26px;
	text-align: center;
}
.child-wrapper .avatar {
	padding: 3px 0px;
	float: left;
	width: 24%;
}
.child-wrapper .avatar img {
	width: 100%;
}
.child-wrapper .avatar p.name {
	text-align: center;
	margin: 0px;
	padding: 0px;
	margin-top: 3px;
}
.child-wrapper .info {
	float: left;
	width: 40%;
}
.child-wrapper .contact {
	padding: 3px 0px;
	float: left;
	width: 28%;
}
		</style>
		<script type="text/javascript">
'use strict';
$(function() {

	var childTpl = Handlebars.compile( $('#child-tpl').html() );
	var data = {
		name: 'ddio',
		sex: '男',
		avatar: 'http://www.missingkids.org.tw/miss_focusimages/98253_s.jpg',
		currentAge: 20,
		missingAge: 8,
		missingDate: '民國77年1月',
		missingRegion: '新北市板橋區',
		missingCause: '被拐(抱)走 ',
		missingLocation: '街道',
		character: '眉粗、眼大'
	};
	$('#wrapper').html( childTpl(data) );

});
		</script>
	</head>
	<body>
		<div id="wrapper"></div>
	</body>
</html>
