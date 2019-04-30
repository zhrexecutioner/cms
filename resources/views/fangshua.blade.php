<form action="/fangshua" method="post">
	{{csrf_field()}}
	<input type="text" name="fangshuatime">超时时间
	<input type="text" name="fangshuanum">频率
	<button type="submit">提交</button>
</form>