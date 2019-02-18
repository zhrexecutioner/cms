<form action="/users/update" method="post">
	{{csrf_field()}}
	<input type="test" name="username">请输入您要更改密码的用户名</br>
	<input type="password" name="passworda">原密码</br>
	<input type="password" name="password">新密码</br>
	<button type="submit">确认更改</button>
</form>