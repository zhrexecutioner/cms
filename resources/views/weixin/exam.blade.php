<script src="http://zhr.phpclub.top/js/jquery-1.11.2.min.js"></script>
<form class="form-signin" action="/weixin/create_menuexam" method="post">
	{{csrf_field()}}
    <button class="first" id="first">一级按钮</button>名字：<input type="text" id="name1" name="name1"><button class="clone1" id="clone1">克隆</button><br>
    <input class="second" id="second" type="button" value="二级按钮"><button class="clone2" id="clone2">克隆</button><br>
    按钮类型：<input type="text" id="zhr" name="zhr"><br>
    二级按钮名字：<input type="text" id="name2" name="name2"><br>
    二级按钮url：<input type="text" id="url" name="url"><br>
    二级按钮名字key：<input type="text" id="key" name="key"><br>
    <button type="submit">提交</button>
</form>
<script>
	$("#clone2").click(function(){
         $('.second').css('display','inline');
    });
</script>