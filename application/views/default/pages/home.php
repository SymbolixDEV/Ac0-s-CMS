<div class="post">
<div class="post_header"><div class="post_title">Vote Pages</div></div>
<div class="post_bg">
<div class="post_text">
    {vote_pages}
</div>
</div>
</div>

{news}
<div class="post">
<div class="post_header"><div class="post_title">{news_title}</div></div>
<div class="post_bg">
<div class="post_text"><img class="post_image" src="{img_url}post_avatar.png" width="133px" height="96px">
{news_content}
<div style="clear: both; display: block;"></div>
<a name="comments"></a>
{news_comments}
{comment_poster} @ {comment_date} : {comment} <div style="clear: both; display: block;"></div>
{/news_comments}
{logged}
{comment_form}
<br />
<form action="{base_url}home/post_comment" method="post" accept-charset="utf-8">
    <input type="hidden" name="news_id" value="{news_id}" />
    <textarea name="comment_textarea" id="comment_textarea" class="comments_textarea" ></textarea>
    <span style="float: left;font-weight: bold;">
        <a href="{base_url}">&lt;&lt;&lt; Back</a>
    </span>
    <span style="float: right;">
        <input type="submit" name="comment_submit" value="Post" id="comment_submit" class="cool"  />
    </span><span class="clear"></span>
</form>
{/comment_form}
{/logged}
</div>
</div>
<div class="post_footer">
<div class="post_footer_comments">Comments {news_comments_count}</div>
<div class="post_footer_txt">Posted by {news_poster} {news_date}</div></div>
</div>
{/news}
<div class="pages">
    <center>
     {pages}
    </center>
</div>
