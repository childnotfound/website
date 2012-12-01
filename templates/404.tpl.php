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
	<div class="clearer"></div>
</div>
<p class="contact">資料來源：兒童福利聯盟基金會，403台中市西區自由路一段98-1號2樓<br />
		 TEL：886-4-22265905，Email: missing@cwlf.org.tw </p>
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

.notfound-wrapper p.contact {
	text-align: right;
	padding: 2px;
	margin: 16px 16px;
	font-size: 16px;
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
	width: 10%;
	padding: 1%;
	font-size: 26px;
	text-align: center;
}
.child-wrapper .avatar {
	padding: 3px 0px;
	float: left;
	width: 30%;
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
	width: 58%;
}
		</style>
		<script type="text/javascript">
'use strict';
$(function() {

	var childTpl = Handlebars.compile( $('#child-tpl').html() );
    $.getJSON('/404.json', function(data) {
        $('#wrapper').html( childTpl(data) );
    });

});
		</script>
	</head>
	<body>
		<div id="wrapper"></div>
	</body>
</html>
