
{include file="header.tpl"}

<div class="side_left">
    <div class="user">
        <div class="image">イメージ入ります。</div>
        {include file="user_info.tpl"}
    </div>
</div>

<div class="main_center user">
    <ul class="message_list">
        {foreach from=$posts item=d}
            <li class="message"><span>{$d[0]['name']|default:''}</span><span>{$d[0]['reg_date']|default:''}</span></br>
                {$d[0]['message']|default:''}</li>
        {/foreach}
    </ul>
</div>

<div class="side_right rondom_user">
    <h2>おすすめユーザー</h2>
    <ul class="rondom_user_list">
        {foreach from=$user_rmdom_data item=r}
            <li><a href="index.php?type=users&id={$r['id']}">イメージ/{$r['name']}</a></li>
        {/foreach}
    </ul>
    
</div>


{literal}
<script type="text/javascript">
$(function() {
    $( 'a[rel*=leanModal]').leanModal({
        top: 50,                     
        overlay : 0.5,               
        closeButton: ".modal_close"  
    });
}); 
$(function(){
    var param1 = $('.message').val();
    console.log(param1);
    $('.tweet_button').click(function(){
        
       $.ajax({
          type: "POST",
          url: "index.php",
          data: { parameter1:param1 }
       }); 
    });
});

</script>
{/literal}
</body>
</html>