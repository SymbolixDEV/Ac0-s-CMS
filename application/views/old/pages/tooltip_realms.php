{realms}
	<table border="0" width="280px" style="text-align: left; font-family:Verdana; font-size: 12;">
		<tr>
			<td class="left">Status</td>
			<td class="right">{realm_status}</td>
		</tr>
		{realm_status_bar}
		<tr height="20px"></tr>
		<tr>
			<td class="left">Uptime</td>
			<td class="right">{realm_uptime}</td>
		</tr>
		<tr>
			<td class="left">{realm_online_allyance} Alliance</td>
			<td class="right">Horde {realm_online_horde}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div style="width:250px;height:20px;">
					<div style="float:left;text-align:right;background:url({base_url}content/img/status_bar/ally.png);width:{percents_allyance}%;height:20px;"></div>
					<div style="float:right;text-align:left;background:url({base_url}content/img/status_bar/horde.png);background-position:right;width:{percents_horde};height:20px;"></div>
				</div>
			</td>
		</tr>
		{/realm_status_bar}
	</table>
{/realms}