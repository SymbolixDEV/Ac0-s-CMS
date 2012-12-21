{news}
<div class="news_home" align="left">
   <div class="news_h_up">
    <div class="nh_date" align="left" style="position:absolute;"><font color="#d4d4d4">posted:</font> {news_date} </div>
	<div class="nh_date" align="right" ><font color="#d4d4d4">by:</font> {news_poster} </div>
   </div>
   <div class="news_h_tittle">
    {news_title}
   </div>
    <div class="nh_line"></div>
   <div class="news_h_text">
   {news_content} 
   </div>
   <div class="nh_line"></div>
   <a name="comments"></a>
    
    {news_comments}
	<div class="news_h_text">
{comment_poster} @ {comment_date} : {comment} <div style="clear: both; display: block;"></div>
</div>
{/news_comments}

{logged}
<div class="nh_line"></div>
    <div class="news_h_text">
{comment_form}

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
</div>
{/logged}
  
   <div class="news_h_end"></div>   
  </div>
{/news}
<div class="pages">
    <center>
     {pages}
    </center>
</div>
