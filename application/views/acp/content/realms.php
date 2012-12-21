<br />
{realms}
<a href="{base_url}index.php/status/index/{realm_id}">{realm_name}</a>
<hr></hr>
<li>Status: <strong><span class="realm_{realm_status}">{realm_status}</strong></span>
<li>Online Players: <strong>{realm_online_players}</strong></li>
<li>GMs Online: <strong>{realm_gm_online}</strong></li>
<li>Max Players Online: <strong>{realm_max_players}</strong></li>
<li>{realm_status_bar}Faction Ratio: <strong><span style="color: green;">{percents_allyance}%</span></strong> - <strong><span style="color: red;">{percents_horde}%</span></strong>{/realm_status_bar}</li>
<li>Uptime: <strong>{realm_uptime}</strong></li>
{/realms}