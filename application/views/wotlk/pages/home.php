<div class="slider">
	<script src="{base_url}application/views/wotlk/js/jquery-1.js" type="text/javascript"></script>
	<script src="{base_url}application/views/wotlk/js/jquery_003.js" type="text/javascript"></script>
	<script src="{base_url}application/views/wotlk/js/jquery.js" type="text/javascript"></script>
	<script src="{base_url}application/views/wotlk/js/jquery_002.js" type="text/javascript"></script>
	<script type="text/javascript">
		var slider = jQuery.noConflict();
		slider(document).ready(function(){
			slider('.box_skitter').skitter({
				dots: false,
				fullscreen: false,
				label: true,
				interval:6000,
				navigation:true,
				label:true, 
				numbers:true,
				hideTools:true,
				thumbs: false,
				velocity:1,
				animation: "random",
				numbers_align:'left',
				animateNumberOut: {backgroundColor:'#333', color:'#fff'},
				animateNumberOver: {backgroundColor:'#000', color:'#fff'},
				animateNumberActive: {backgroundColor:'#CE3E0C', color:'#fff'}
			}); 
		});	
	</script>
	<div class="box_skitter">
		<ul style="display: none;">
			<li><img src="{base_url}application/views/wotlk/img/example1.jpg" class="random"><div class="label_text">
				<h5>Gamers-Playground</h5><p>Where the challenges begins !</p></div></li>
			<li><img src="{base_url}application/views/wotlk/img/example2.jpg" class="random"><div class="label_text">
				<h5><a href="{base_url}index.php/register">Register Now</a></h5><p>Easily create an account, with no email registration required (email can be provided optionally though, to assist with any account recovery requests), then set your realmlist to <b>{realmlist}</b>, to quickly join our community.</p></div></li>
			<li><img src="{base_url}application/views/wotlk/img/example3.jpg" class="random"><div class="label_text">
				<h5>Created by wotlk</h5><p>You can keep the slideshow effects random, or you can choose a unique effect. You're also able to set time interval between slides.</p></div></li>
		</ul>
	</div>
</div>
<div class="box">
	<div class="box_title">{lang_vote_sites}</div>
	{vote_pages}
</div>
{news}
	<div class="box">
		<div class="box_image"></div>
		<div class="box_title">{news_title}.</div>
		{news_content}
	</div>
{/news}
{pages}