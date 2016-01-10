<?php
/**
 * upload a file 
 *
 * this file uploads a file 
 * 
 * PHP version 5.2+
 *
 * LICENCE: This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version
 *
 * @category   bmp\sources\ajax handlers
 * @package    bmp\sources
 * @author     Original Author <gdimi@hyperworks.gr>
 * @copyright  2014-2016 George Dimitrakopoulos
 * @license    GPLv2
 * @version    1.0
 * @link       -
 * @see        -
 * @since      Since 0.438-dev
 * @deprecated -
 */
if (!defined('_w00t_frm')) die('har har har');

$scerr = '';
$msg = '';
$pos = $_GET['pos'];
$caseId = (int)$_POST['cid'];

if (!$pos or $pos != 'before') {
	$scerr = 'Task ['.$task.'] warning: no or wrong position of execution';
} else {
	if (!$caseId || $caseId < 0) {
		//$scerr = 'No or invalid case id supplied!';
		$target_dir = "content/uploads/tmp";
	} else {
		$target_dir = "content/uploads/${thisYear}/${caseId}";
	}

	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$fileSize = $_FILES["file"]["size"];
	$fileTmpName = $_FILES["file"]["tmp_name"];

	$scerr = validate_upload($target_dir,$fileType,$fileTmpName,$fileSize,$dss->maxUploadSize,$dss->uploadTypes);

	// Check if we have an error and if not try to upload the file!
	if (!$scerr) {
		if (move_uploaded_file($fileTmpName, $target_file)) {
			$msg = "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
			$tk_status = json_encode(array(
			 'status' => 'success',
			 'message'=> $msg
			));
			echo $tk_status;
			exit(0);
		} else {
			$scerr = "Sorry, there was an error uploading your file.";
		}
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

function validate_upload($target_dir,$fileType,$fileTmpName,$fileSize,$maxUploadSize,$uploadTypes=array()) {
    
    clearstatcache(); //to avoid file_exists false reports

	if (!file_exists($target_dir)) {
		if (!mkdir($target_dir, 0777, true)) {
			return 'could not create directory:'.$target_dir;
		}
	}

	// Check if image file is a actual image or fake image
	if($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'bmp' || $fileType == 'png' || $fileType == 'tiff' || $fileType == 'gif') {
		$check = getimagesize($fileTmpName);
		if($check === false) {
			return "File is propably a fake image.";
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		return "Sorry, file already exists.($target_file)";
	}
	// Check file size
	if ($fileSize > ($maxUploadSize * 1024)) {
		return "Sorry, your file is too large. Maximum size allowed is:".$maxUploadSize." kbytes";
	}
	// Allow certain file formats
	if(!in_array($fileType,$uploadTypes)) {
		return  "Sorry, this file type ($fileType) is not allowed.";
	}
}

?>
