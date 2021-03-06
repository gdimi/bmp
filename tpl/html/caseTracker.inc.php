		<div id="ct_div">
		<table id="ct_table">
			<thead>
				<tr class="thead">
					<th>Cid</th>
					<th>Created</th>
					<th><a href="index.php?action=docache&sr=updated">Updated</a></th>
					<th>Title</th>
					<th><a href="index.php?action=docache&sr=model">model/SN</a></th>
					<th><a href="index.php?action=docache&sr=tag">Tag</a></th>
					<th><a href="index.php?action=docache&sr=client">Client</a></th>
					<th><a href="index.php?action=docache&sr=status">Status</a></th>
					<th><a href="index.php?action=docache&sr=prior">Priority</a></th>
					<th><a href="index.php?action=docache&sr=type">Type</a></th>
					<th>Price</th>
					<th><a href="index.php?action=docache&sr=user">User</a></th>
					<th>Action</th>
				</tr>
			</thead>
		<?php
		if (isset($tickets) && is_array($tickets)) {
            $ticket_data = '';
            $actual_ticket_data = '';
            $js = '';
            
			$total = count($tickets);
			//if (!$sort) {
			//	krsort($tickets,SORT_NUMERIC); // sort  reversed and numeric by key, keys are the stored ids 
			//}
			foreach($tickets as $key=>$ticket) {
				$tstat = $dss->caseStatus[$ticket['status']];
				$tstat_class = str_replace(' ','_',$tstat);
				$ttype = $dss->caseType[$ticket['type']];
                $tcid = $ticket['type'].$key; //this is the case reference id
                if ($ticket['type'] < 10) { //if id less than ten add a zero so to fix id length to 2 chars
                    $tcid = '0'.$tcid;
                }
				$tdate = date("j/m/Y H:i",$ticket['created']);
				$tudate = date("j/m/Y H:i",$ticket['updated']);
				//see priority so to put correct class
				if ($ticket['priority'] == 1) { $tprior_class = 'Low'; } elseif ($ticket['priority'] == 2) { $tprior_class = 'Medium'; } else { $tprior_class = 'High'; }

				$tprior = $dss->casePriority[$ticket['priority']];
				
				if ($ticket['attachment']) {
					$attachHTML = $lang['attachment'];
					foreach ($ticket['attachment'] as $attached) {
						$attachHTML .= '&nbsp; <a href="'.$defUploadDir.'/'.$key.'/'.$attached.'" target="_blank">'.$attached.'</a>&nbsp;'; // use $key for actual db id and not constructed $tcid
					}
                    $att_class = 'hasAttachment';
				} else {
                    $att_class = '';
                    $attachHTML = '';
				}
				
				$style = '';
				//no display for closed or unfixable by default
				if ($tstat == 'Closed' || $tstat == 'Unfixable') {
					$style = 'style="display:none"';
				}

				$ticket_data .=  "
				<tr class=\"tbody $tstat_class $tprior_class ${ticket['user']} cl-${ticket['client']}\" $style>
					<td>$tcid</td>
					<td>$tdate</td>
					<td>$tudate</td>
					<td class=\"${att_class}\"><a href=\"javascript:void(0);\" id=\"ctdummyhref_$key\">${ticket['title']}</a></td>
                    <td>${ticket['model']}</td>
					<td>${ticket['cat']}</td>
					<td><a href=\"javascript:void(0);\" class=\"cclient\" id=\"cl_${ticket['client']}\">${ticket['name']}</a></td>
					<td class=\"ct-stat${ticket['status']}\">${tstat}</td>
					<td class=\"ct-$tprior_class\">$tprior</td>
					<td class=\"ct-type${ticket['type']}\">${ttype}</td>
					<td class=\"ct-price\">${ticket['price']}</td>
					<td class=\"ct-user\">${ticket['user']}</td>
					<td><span title=\"close this case\" class=\"close-tck\" id=\"ct_s${key}\">C</span>&nbsp;<span title=\"delete this case\" class=\"del-tck\" id=\"dt_s${key}\">D</span>
                    
                    </td>
				</tr>";
				
				if ($tstat != 'Closed') {
					$js .= '
					$("#ctdummyhref_'.$key.'").click(function() {
						$("#cur_ticket_'.$key.'").toggle("slow");
					});//unlock ticket
					$("#unlock_tk_'.$key.'").click(function() {
						$("#edit_tk_'.$key.'").find(":input").prop("disabled",false).addClass("frm-input");
						$("#lock_tk_'.$key.'").toggle();
						$("#edtckbtn'.$key.'").toggle();
						$(this).hide();
					});//lock ticket
					$("#lock_tk_'.$key.'").click(function() {
						$("#edit_tk_'.$key.'").find(":input").prop("disabled","disabled").removeClass("frm-input");
						$("#unlock_tk_'.$key.'").toggle();
						$("#edtckbtn'.$key.'").toggle();
						$(this).hide();
					});';
				}

				$js .= '
				//del ticket
				$("#dt_s'.$key.'").click(function(){
					$("#dt_tk_msg").html("Do you want really to delete ticket <strong>'.$key.'</strong> - <strong>'.$ticket['title'].'</strong> ?");
					$(".del-tk-b").attr("id","dt_'.$key.'"); //change current id to include case id
					$("#dt_tk").show();
				});
				$("#ct_s'.$key.'").click(function(){
					$("#ct_tk_msg").html("Do you really want to close ticket <strong>'.$key.'</strong> - <strong>'.$ticket['title'].'</strong> ?");
					$(".close-tk-b").attr("id","dt_'.$key.'"); //change current id to include case id
					$("#ct_tk").show();
				});';

				if ($tstat != 'Closed')	{
						$actual_ticket_data .= '
					<div id="cur_ticket_'.$key.'" class="div-elevate .'.$tstat.'">
					<span class="cl-b b-blue" onclick="$(this).parent().toggle();">Close me</span>
					<span class="cl-b b-blue" id="unlock_tk_'.$key.'" style="clear:right;">Unlock me</span>
					<span class="cl-b b-blue" id="lock_tk_'.$key.'" style="display:none;clear:right;">Lock me</span>
					<form name="edit_tk_'.$key.'" id="edit_tk_'.$key.'" class="frm-actual-data">
						<h4 class="">Case '.$key.' : <input type="text" name="title" value="'.$ticket['title'].'" maxlength="255" required disabled  pattern=".{5,}" /></h4>
						<div class="ct-details">
							<span class="ct-date"><input type="text" name="date" value="'.$tdate.'" maxlenght="20" size="10" disabled required /> | </span>
							<span class="ct-cat"><input type="text" name="cat" value="'.$ticket['cat'].'" disabled required /> | </span>
							<span class="ct-flags">
							<select name="epriority" id="etk_priority" disabled required>
								<option></option>';
									foreach ($dss->casePriority as $skey=>$value) {
										if ($value == $tprior) {
											$sel = 'selected';
										} else {
											$sel = '';
										}
										$actual_ticket_data .= '<option value="'.$skey.'" '.$sel.'>'.$value.'</option>';
									}
							$actual_ticket_data .= '</select>
							<select name="etype" id="etk_type" disabled required>
								<option></option>';
									foreach ($dss->caseType as $ctype=>$value) {
										if ($value == $ttype) {
											$sel = 'selected';
										} else {
											$sel = '';
										}
										$actual_ticket_data .= '<option value="'.$ctype.'" '.$sel.'>'.$value.'</option>';
									}
							$actual_ticket_data .='</select>
							<select name="estatus" id="etk_status" disabled required>
								<option></option>';
									foreach ($dss->caseStatus as $cstat=>$value) {
										if ($value == $tstat) {
											$sel = 'selected';
										} else {
											$sel = '';
										}
										$actual_ticket_data .='<option value="'.$cstat.'" '.$sel.'>'.$value.'</option>';
									}
							$actual_ticket_data .='</select>
							</span>
						</div>
						<div class="ct-model"> <label for="model">Model/SN:</label> <input type="text" name="model" value="'.$ticket['model'].'" maxlength="255"  disabled /></div>
						<div class="ct-info"> <label for="info">Info:</label> <textarea name="info" class="rich" disabled>'.$ticket['info'].'</textarea></div>
						<div class="ct-client"> <label for="client">Client:</label> <input type="text" name="client" value="'.$ticket['name'].'" maxlength="255" class="etcl" id="ecl_'.$key.'"disabled required /><div id="etk_cl_res_'.$key.'" class="autocomplete" style="display:none;"></div></div>
						<div class="ct-price"> <label for="price">Price:</label> <input type="text" name="price" value="'.$ticket['price'].'" maxlength="4"  disabled /><br /></div>
						<div class="ct-follow"><label for="user">Follow:</label> <input type="text" name="follow" disabled value="'.$ticket['follow'].'" /></div>
						<div class="ct-user"> <label for="user">User:</label> <input type="text" name="user" disabled value="'.$ticket['user'].'" /></div>
						<div class="ct-attachment">'.$attachHTML.'</div>
						<div class="ct-update"><input type="checkbox" name="ctupdate" value="1" style="width:auto" disabled> Don\'t change updated date </div>
						<div class="ct-notify"><input type="checkbox" name="ctnotify" value="1" style="width:auto" disabled> Don\'t send notification about this update </div>
						<input type="hidden" name="cid" value="'.$ticket['client'].'" id="et_client_'.$key.'"/>
						<input type="hidden" name="tid" value="'.$key.'" id="et_id_'.$key.'"/>
						<div id="ed_tk_frm_err_'.$key.'" style="color:red;border:medium solid red;padding:8px;display:none;"></div>
						<span class="fake-button edtckbtn" id="edtckbtn'.$key.'">Save</span><br /><br />
					</form>
					<div id="ed_tk_frm_err_'.$key.'"></div>
					</div>';
				}
			}
			$ticket_data .='</table></div>
				<div id="dt_tk" style="display:none;" class="elevate menu-dialog">
					<h2>DELETE TICKET!</h2>
					<div id="dt_tk_msg"></div><br /><br />
					<span class="cl-b b-sblue del-tk-b" id="">YES</span>
					<span class="cl-b b-sblue" onclick="document.getElementById(\'dt_tk\').style.display = \'none\';">NO</span>
				</div>
				<div id="ct_tk" style="display:none;" class="elevate menu-dialog">
					<h2>Close this ticket</h2>
					<div id="ct_tk_msg"></div><br /><br />
					<span class="cl-b b-sblue close-tk-b" id="">YES</span>
					<span class="cl-b b-sblue" onclick="document.getElementById(\'ct_tk\').style.display = \'none\';">NO</span>
				</div>';

			$cache->cacheData = $ticket_data.$actual_ticket_data.'<script>$(document).ready(function() {'.$js.'});</script>';
			if (!$cache->doCache()) { 
				echo '<span style="color:red">Cache error:'.$cache->error.'</span>';
			}
			echo $ticket_data.$actual_ticket_data.'<script>$(document).ready(function() {'.$js.'});</script>';
		} else {
			include($cache->cachefilename);
		}
    ?>
