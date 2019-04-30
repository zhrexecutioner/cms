<form action="/shijian" method="post">
	{{csrf_field()}}
	<input type="text" name="fangshuatime">超时时间</br>
	<input type="text" name="fangshuanum">频率</br>
	<button type="submit">提交</button>
</form>