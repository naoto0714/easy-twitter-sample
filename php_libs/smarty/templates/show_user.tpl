{include file="header.tpl"}

#以下コンテンツ
<div class="top">
    <div class="top_image"></div>
    
        {include file="user_info.tpl"}
        <form action="" method="post">
            <input type="hidden" name="action" class="following_toggle" value="{$f_result|default:'following'}">
            <button class="following">フォローする</button>
        </form>
</div>



<div class="side_left">
    <div class="user">
        <div class="image">イメージ入ります。</div>
        <div class="user_info">
            <h4><a href="index.php?type=show&id={$userdata['id']}">{$userdata['name']}</a></h4>
        </div>
    </div>
</div>

<div class="main_center user">
    <ul class="message_list"
        {foreach from=$data item=d}
            <li class="message"><span>[name]</span><span>{$d['reg_date']}</span></br>
                {$d['message']}</li>
        {/foreach}
    </ul>
</div>

<div class="side_right user">
    <h2>フォロー</h2>
    {foreach from=$following item=fwing}
        <li><a href="index.php?type=users&id={$fwing['id']}">イメージ/{$fwing['name']}</a></li>
    {/foreach}
    <h2>フォロワー</h2>
    {foreach from=$follower item=fwer}
        <li><a href="index.php?type=users&id={$fwer['id']}">イメージ/{$fwer['name']}</a></li>
    {/foreach}
</div>


#follower
{var_dump($follower)}
#followin
{var_dump($following)}
{var_dump($userdata)}
#data
{var_dump($data)}
#result
{var_dump($f_result)}
#count
{var_dump($count)}