<?php include 'head.php';?>
<div id="welcomebox">
<div id="registerbox">
<h2>ע��!</h2>
<b>������Retwis? ��ע���˺�!</b>
<form method="POST" action="register.php">
<table>
<tr>
  <td>�û���</td><td><input type="text" name="username"></td>
</tr>
<tr>
  <td>����</td><td><input type="password" name="password"></td>
</tr>
<tr>
  <td>����(again)</td><td><input type="password" name="password2"></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="submit" name="doit" value="ע��"></td></tr>
</table>
</form>
<h2>�Ѿ�ע����? ��ֱ�ӵ�½</h2>
<form method="POST" action="login.php">
<table><tr>
  <td>�û���</td><td><input type="text" name="username"></td>
  </tr><tr>
  <td>����:</td><td><input type="password" name="password"></td>
  </tr><tr>
  <td colspan="2" align="right"><input type="submit" name="doit" value="Login"></td>
</tr></table>
</form>
</div>
����! Retwis  ��һ���򵥵�<a href="http://twitter.com">Twitter</a>��¡, Ҳ��<a href="http://code.google.com/p/redis/">Redis</a> key-value ���ݿ��һ��ʹ�ð�ȫ. �ؼ���:
<ul>
<li>Redis ��һ��key-value ���ݿ�, �����Ǳ���Ŀ�� <b>Ψһ</b>ʹ�õ����ݿ�, û����mysql��.</li>
<li>Ӧ�ó������ͨ��һ���Թ�ϣ���׵Ĳ����̨������</li>
<li>PHP��redis��������������pecl�Ĺٷ���չ<a href="pecl.php.net/package/redis">php-redis</a>
</ul>
</div>
<?php include 'food.php';?>