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
			<input type="text" name="username" id="username" placeholder="Username" />
			<input type="password" name="password" id="password" placeholder="Password" />
			<input type="submit" name="submit" id="loginFormSubmit" value="Login">
		</form>
	</div>
	<div class="searchForm hide">
		<h1>Type your search</h1>
		<form id="search" method="POST">
			<input type="text" name="jql" id="jql" placeholder="JIRA Query">
			<input type="submit" name="submit" id="searchFormSubmit" value="Search">
		</form>
	</div>
	<div class="result">
		<!--QUERY RESULT WILL BE INSERTED HERE-->
	</div>
</body>
</html>