<div class="title">{title}</div>
<div class="content">
	{status}
	<center>
        <table style="min-width: 410px;">
			<form action="{base_url}index.php/forget/validation_forget" method="post" accept-charset="utf-8">                    
				<tr>
					<td class="aleft"><label for="forget_username" style="cursor:pointer;">{lang_username}</label></td>
					<td class="aleft"><input type="text" name="forget_username" value="" id="forget_username" class="cool_textfield" style="width: 300px;"  /></td>
				</tr>
				<tr>
					<td class="aleft">{lang_security_question}</td>
					<td class="aleft">
						<input type="radio" name="forget_security_question" value="1"  /> Your middle name?<br />
						<input type="radio" name="forget_security_question" value="2" checked="checked"  /> Your birth town?<br />
						<input type="radio" name="forget_security_question" value="3"  /> Your pet's name?<br />
						<input type="radio" name="forget_security_question" value="4"  /> Your mother maiden name?<br />
					</td>
				</tr>
				<tr>
					<td class="aleft"><label for="forget_security_answer" style="cursor:pointer;">{lang_security_answer}</label></td>
					<td class="aleft"><input type="text" name="forget_security_answer" value="" id="forget_security_answer" class="cool_textfield" style="width: 300px;"  /></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td class="aleft">
						<a href="{base_url}index.php/register">{lang_register_account}</a>
					</td>
					<td class="aright">
						<input type="submit" name="forget_submit" value="{lang_submit}" id="forget_submit" class="cool_button"  />
					</td>
				</tr>
			</form>                           
		</table>
	</center>
</div>