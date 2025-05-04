<!DOCTYPE html>
<html>
<head>
	<title>Login Callback</title>
</head>
<body>
<script>
	window.opener.postMessage({
		token: @json($token),
		user: @json($user),
	}, window.origin);
	window.close();
</script>
</body>
</html>
