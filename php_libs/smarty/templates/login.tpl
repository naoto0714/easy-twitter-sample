
<h1>ログイン画面</h1>

<form action="" method="post">
    <span>{$auth_error_mess}</span>
    <input type="text" name="username" value="" placeholder="メールアドレス">
    <input type="password" name="password" value="" placeholder="パスワード">
    <input type="hidden" name="type" value="{$type}">
    <button>ログイン</button><span><a href="index.php?type=regist">49ersを始める</a></span>
</form>

