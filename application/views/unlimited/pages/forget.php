<div class="sub_table" align="left">
   <div class="sub_t_up"></div>
   <div class="sub_t_tittle">
   {title}
   </div>
    <div class="sub_p_line"></div>
   <div class="sub_p_text">
    <div class="register_info_1">
{register_status}
        <table style="color: #FFFFFF; min-width: 505px;">
			<form action="{base_url}forget/validation_forget" method="post" accept-charset="utf-8">                    
				<tr>
					<td class="aleft"><label for="forget_username" style="cursor:pointer;">Username</label></td>
					<td class="aleft"><input type="text" name="forget_username" value="" id="forget_username" class="cool" style="width: 300px;"  /></td>
				</tr>
				<tr>
					<td class="aleft">Security Question</td>
					<td class="aleft">
						<input type="radio" name="forget_security_question" value="1"  /> Your middle name?<br />
						<input type="radio" name="forget_security_question" value="2" checked="checked"  /> Your birth town?<br />
						<input type="radio" name="forget_security_question" value="3"  /> Your pet's name?<br />
						<input type="radio" name="forget_security_question" value="4"  /> Your mother maiden name?<br />
					</td>
				</tr>
				<tr>
					<td class="aleft"><label for="forget_security_answer" style="cursor:pointer;">Security Answer</label></td>
					<td class="aleft"><input type="text" name="forget_security_answer" value="" id="forget_security_answer" class="cool" style="width: 300px;"  /></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td class="acenter" valign="middle" colspan="2">
						<span class="left"><a href="{base_url}register">Register</a></span>
						<span class="right"><input type="submit" name="forget_submit" value="Submit" id="forget_submit" class="cool"  /></span>
                                                <span class="clear"></span>
					</td>
				</tr>
			</form>                           
		</table>
	</div>
   </div>
   <div class="sub_p_end"></div>   
  </div>