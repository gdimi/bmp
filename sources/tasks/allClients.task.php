<?php
//delete a ticket
if (!defined('_w00t_frm')) die('har har har');
$pos = $_GET['pos'];

if (!$pos or $pos != 'before') {
	$scerr = 'Task ['.$task.'] warning: no or wrong position of execution';
} else {
	try {
		$sccon = new PDO('sqlite:pld/HyperLAB.db3');
		$sccon->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$scres = $sccon->query('SELECT id,name,tel1,tel2,email FROM "Client" ORDER BY name;');
		if ($scres) {
			$msg = '<div class="grid">';
			foreach ($scres as $client) {
                if (!$client['email']) {
                    $cmail = 'no mail';
                } else {
                    $cmail = $client['email'];
                }
			  $msg .= '<div><a class="show-client" href="javascript:$(this).preventDefault();" id="cl'.$client['id'].'">'.$client['name'].' ('.$client['id'].')</a> - '.$client['tel1'].' | '.$client['tel2'].' - '.$cmail.'</div>';
			}
			$msg .= '</table>';
			$tk_status = json_encode(array(
			 'status' => 'success',
			 'message'=> $msg
			));
			echo $tk_status;
			exit(0);
		}
	} catch(PDOException $ex) {
		$scerr = "An Error occured!".$ex->getMessage();
	}
}

if ($scerr) {
	$tk_status = json_encode(array(
	 'status' => 'error',
	 'message'=> $scerr.'<br />'
	));
	echo $tk_status;
	exit(1);
}
?>
