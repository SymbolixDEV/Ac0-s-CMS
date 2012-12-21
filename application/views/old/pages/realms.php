<div id="realms_wtf">
{realms}
	<div class="left"><a href="{base_url}status/index/{realm_id}" onmouseover="Tooltip.show(this, '{base_url}ajax/show_realm_info/{realm_id}');"  >{realm_name}</a></div><div class="right">{realm_online_players}/{realm_player_limit}</div><div class="clear"></div>
	<div class="bar"><div class="filled" style="width:{realm_total_online_percent}%"></div></div>
	<br />
{/realms}
</div>