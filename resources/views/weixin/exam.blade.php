<script src="http://zhr.phpclub.top/js/jquery-1.11.2.min.js"></script>
<form action="" class="form-inline">
    <button class="first" id="first">一级按钮</button>名字：<input type="text" id="name1"><button class="clone1" id="clone1">克隆</button><br>
    <button class="second" id="second" display="none">二级按钮</button><button class="clone2" id="clone2">克隆</button><br>
    按钮类型：<select name="" id="">
				<option value="click">click</option>
				<option value="view">view</option>
    		 </select><br>
    二级按钮名字：<input type="text" id="name2"><br>
    二级按钮url：<input type="text" id="url"><br>
    二级按钮名字key：<input type="text" id="key"><br>
    <button class="submit" id="submit">提交</button>
</form>
<script>
	$("#clone2").click(function(){
         $('.second').css('display','block');
    });
</script>