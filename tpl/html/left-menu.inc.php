		<h3><?php echo $lang['lmenu']; ?></h3>
        <ul>
            <li>
				<span class="mcap">
					<a href="javascript:void(0);" onclick="$('#new_ticket').toggle();"><?php echo $lang['lmenu-add-case']; ?></a>
				
				<span" id="add_tk" title="add a new case" alt="add a new case"><img src="images/add.png" width="24" /></span></span>
			</li>
            <li>
				<span class="mcap">
					<a href="javascript:void(0);" onclick="$('#new_client').toggle();"><?php echo $lang['lmenu-add-client']; ?></a>
				
				<span id="add_client"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#allclients').toggle();"><?php echo $lang['lmenu-all-clients']; ?></a>
				<span id="all_clients"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#esoda').toggle();"><?php echo $lang['lmenu-income']; ?></a>
				<span id="income"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#costs').toggle();"><?php echo $lang['lmenu-expenses']; ?></a>
				<span id="expenses"></span></span>
			</li>
            <li style="display:none;">
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#events').toggle();"><?php echo $lang['lmenu-events']; ?></a></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#cms_info').toggle();"><?php echo $lang['lmenu-cms']; ?></a>
				<span id="cms"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#stats_info').toggle();"><?php echo $lang['lmenu-stats']; ?></a> 
				<span id="stats"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#settings').toggle();"><?php echo $lang['lmenu-settings']; ?></a>
				<span id="settings_tk"></span></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#various').toggle();"><?php echo $lang['lmenu-various']; ?></a></span>
			</li>
            <li>
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#prices').toggle();"><?php echo $lang['lmenu-prices']; ?></a></span>
			</li>
            <li style="display:none;">
				<span class="mcap"><a href="javascript:void(0);" onclick="$('#this_todo').toggle();"><?php echo $lang['lmenu-todo']; ?></a></span>
			</li>
        </ul>
