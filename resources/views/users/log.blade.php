<form action="/users/login" method="post">
	{{csrf_field()}}
	<input type="text" name="username">用户名</br>
	<input type="password" name="password">密码</br>
	<button type="submit">登录</button>
</form>