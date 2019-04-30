<table border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td width="315px" class="tdColor">用户名</td>
		<td width="315px" class="tdColor">token</td>
		<td width="125px" class="tdColor">操作</td>
	</tr>
	@foreach($data as $k=>$v)
	<tr>
		<td>{{$v->name}}</td>
		<td>{{$v->remember_token}}</td>
		<td>
			<a href="/heiadd/{{$v['openid']}}">加入黑名单</a>
		</td>
	</tr>
	@endforeach
</table>