<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<table border="1">
		<tr>
			<td>商品名称</td>
			<td>商品库存</td>
			<td>商品价格</td>
			<td>操作</td>
		</tr>
		@foreach($list as $v)
		<tr>
			<td>{{$v->goods_name}}</td>
			<td>{{$v->store}}</td>
			<td>{{$v->price}}</td>
			<td><a href="/goods/{{$v->goods_id}}">详情</a></td>
		</tr>
		@endforeach
	</table>
	<a href="/cart">进入购物车</a>
</body>
</html>
<script>
	function 
</script>