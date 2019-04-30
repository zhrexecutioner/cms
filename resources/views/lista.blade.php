<table border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td width="315px" class="tdColor">用户名</td>
		<td width="315px" class="tdColor">token</td>
		<td width="315px" class="tdColor">还需多久过期</td>
		<td width="125px" class="tdColor">操作</td>
	</tr>
	@foreach($data as $k=>$v)
	<tr>
		<td class="">{{$v->name}}</td>
		<td class="token">{{$v->remember_token}}</td>
		<td><input type="text" class="text2"/></td>
		<td>
			<button class="btn_ok btn_yes" href="#" name="btn">提交</button>
		</td>
	</tr>
	@endforeach
</table>
<script>
$(document).ready(function(){
	$("button[name='btn']").click(function(){
		var text = $(".text2").val();
		var token = $(".token").val();
		var url = "/sendmsg";
		$.ajax({
			type : "post",			
			dataType : "json",
			url : url,
			data:{text:text,token:token},
			success:function(msg){

			}
		});

	});
});
</script>