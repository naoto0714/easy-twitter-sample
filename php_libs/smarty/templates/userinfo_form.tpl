
    <body>
<p>49ers登録画面</p>
<p>{$message}</p>
<form action="" method="post" class=".form">
    <label for="name">名前:</label>
    <input type="text" name="name" value="" placeholder="名前">
    <label for="username">メールアドレス:</label>
    <input type="text" name="username" value="" placeholder="メールアドレス">
    <label for="name">パスワード:</label>
    <input type="text" name="password" value="" placeholder="パスワード">
    <input type="hidden" name="action" value="{$action}">
    <button class="button">登 録</button>
</form>

    </body>
</html>