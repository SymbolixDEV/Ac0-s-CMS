{realms}
<div class="realm">
	<span class="left" style="font-weight: bold;"><a href="{base_url}index.php/status/index/{realm_id}">{realm_name}</a></span><span class="right realm_{realm_status}" style="font-weight: bold;">{realm_status}</span><span class="clear"></span>
	<div class="bonus">
		<span class="left">Type</span><span class="right">{realm_icon}</span><span class="clear"></span>
		<span class="left">Online</span><span class="right">{realm_online_players}/{realm_player_limit}</span><span class="clear"></span>
		<span class="left">Max Online</span><span class="right">{realm_max_players}</span><span class="clear"></span>
		<span class="left">Total Characters</span><span class="right">{realm_total_chaterters}</span><span class="clear"></span>
		<span class="left">Total Guilds</span><span class="right">{realm_total_guilds}</span><span class="clear"></span>
		<span class="left">Total Arena Teams</span><span class="right">{realm_total_teams}</span><span class="clear"></span>
		<span class="left">Uptime</span><span class="right">{realm_uptime}</span><span class="clear"></span>
	</div>
	<div class="online_box"><div class="fill" style="width: {realm_total_online_percent}%;"></div></div>
</div>
{/realms}
*<small>Status is refreshing every 10 seconds.</small>