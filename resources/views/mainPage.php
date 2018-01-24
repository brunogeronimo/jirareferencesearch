<!DOCTYPE html>
<html>
<head>
	<title>JIRA Warranty Controller - Main</title>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/code.js"></script>
	<script type="text/javascript" src="/js/JiraInterface.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/stylesheet.css">
</head>
<body>
	<div class="loginForm">
		<h1>Login</h1>
		<form id="login" method="POST">
			<input type="text" name="username" id="username" placeholder="Username" value="bgsilva"/>
			<input type="password" name="password" id="password" placeholder="Password" value="Thiogachar#2511"/>
			<input type="submit" name="submit" id="loginFormSubmit" value="Login">
		</form>
	</div>
	<div class="searchForm hide">
		<h1>Type your search</h1>
		<form id="search" method="POST">
			<input type="text" name="jql" id="jql" placeholder="JIRA Query" value="project in (ATFMIN, ATSKYFLOW) and (key in (ATSKYFLOW-356,ATSKYFLOW-352,ATSKYFLOW-361, ATSKYFLOW-348,ATSKYFLOW-363,ATSKYFLOW-362, ATSKYFLOW-360,ATSKYFLOW-357,ATSKYFLOW-355, ATSKYFLOW-354,ATSKYFLOW-344,ATSKYFLOW-271) )">
			<input type="submit" name="submit" id="searchFormSubmit" value="Search">
		</form>
	</div>
	<div class="result">
		<!--QUERY RESULT WILL BE INSERTED HERE-->
	</div>
</body>
</html>