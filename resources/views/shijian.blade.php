<form action="/shijian" method="post">
	{{csrf_field()}}
	<input type="text" name="time">过期时间
	<button type="submit">提交</button>
</form>