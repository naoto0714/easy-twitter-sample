<HTML>
<HEAD>
    <TITLE>{$title}</TITLE>
    {literal}
    <link rel="stylesheet" href="./asset/css/modal.css">
    <link rel="stylesheet" href="./asset/css/darktooltip.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.1.0.js"></script>
    <script src="./asset/js/jquery.leanModal.min.js"></script>
    <script src="./asset/js/jquery.darktooltip.min.js"></script>
    <script src="./asset/js/tooltips.js"></script>
    {/literal}    
</HEAD>
<BODY>
    <HEADER class="header">
        <DIV class="header_left">
            <ul class="header_list">
                <li><a href="index.php">ホーム</a></li>
            </ul>
        </DIV>
        <DIV class="header_right">
            <FORM action="" method="post">
                <INPUT type="text" name="search" value="" placeholder="490ersを検索">
                <BUTTON>検索</BUTTON>
            </FORM>
            <div id="def-html" class="box" data-tooltip="#html-content">デモ</div>
            <div id ="html-content">
                <ul>
                  <li><a href="">プロフィール</a></li>
                  <li><a href="index.php?type=logout">ログアウト</a></li>  
                </ul>
            </div>
            <a rel="leanModal" href="#div787">Tweet</a>
            <div id="div787">
                <form method="post" action="">
                    <h4>490ers</h4>
                    <textarea name="message" class="message"></textarea>
                    <input type="hidden" name="action" value="insert">
                    <BUTTON class=".modal_close tweet_button">tweet</BUTTON>
                </form>
            </div>

        </DIV>
    </HEADER>
