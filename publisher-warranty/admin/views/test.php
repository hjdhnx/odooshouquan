<?php 
print <<<EOT
<h1>个人信息</h1>
     
	 <form method="post"  action="www.baidu.com">
        <p>
		   <lable for="username"> 姓 名：</label>
		      <input type="text" name="" id="username"></p>
        <p>
		  <lable for="password"> 密 码：</label>
		     <input type="password" name=""id="password"></p>
		<p>
		  <lable for="introduce"> 个人描述：</label>
		  <textarea name="commet"rows=5 cols=60  id="introduce">
		  </textarea></p>
		<p>
		   性 别：   <input type="radio" name="性别">男
		    <input type="radio" name="性别">女</p>
	    <p>
		   年 级：   <select name="nianji">
		     <option>one
			 <option>two 
			 <option>three
			 <option>four
			 <option selected>one
			 </select></p>
		<p>
		     爱 好：   <input type="checkbox"name="sport">运动
			<input type="checkbox"name="music">音乐
			    <input type="checkbox"name="movie">电影
			<input type="checkbox"name="read">阅读</p>
		<p>
	     文 件：   <input type="file" name="filename"></p>
		<p>
        <input type="submit"value="提交">
          <input type="reset"value="清空"></p>
     </form>

EOT;

?>