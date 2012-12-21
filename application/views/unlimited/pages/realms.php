{realms}
<div class="right_box" align="left">
   <div class="rb_up_tittle"><a href="{base_url}status/index/{realm_id}">{realm_name}</a></div>
    <div class="rd_info_place">
		<span style="float:left;">Type</span><span style="float:right;">{realm_icon}</span><span style="clear: both; display: block;"></span>
		<span style="float:left;">Uptime</span><span style="float:right;">{realm_uptime}</span><span style="clear: both; display: block;"></span>
		<span style="float:left;">Max Online Players</span><span style="float:right;">{realm_max_players}</span><span style="clear: both; display: block;"></span>
		<span style="float:left;">Online Players</span><span style="float:right;">{realm_online_players}</span><span style="clear: both; display: block;"></span>
    
		{realm_status_bar}
        <div style="width:100%;height:20px;padding:10px 0 10px 0">
            <div style="float:left;text-align:right;background-image: url('{base_url}content/img/status_bar/ally.png');width:{percents_allyance}%;height:20px;">
                <font style="color:#FFFFFF;font-weight:bold;">{percents_allyance_string}&nbsp;</font>
            </div>
            <div style="float:right;text-align:left;background: url('{base_url}content/img/status_bar/horde.png');background-position:right;width:{percents_horde}%;height:20px;">
                <font style="color:#FFFFFF;font-weight:bold;">&nbsp{percents_horde_string}</font>
            </div>
        </div>
    {/realm_status_bar}
	</div>
   <div class="rb_down"></div>   
  </div>
{/realms}
<div style="color: white;padding: 5px;">*<small>Status is refreshing every 10 sec</small>.</div>