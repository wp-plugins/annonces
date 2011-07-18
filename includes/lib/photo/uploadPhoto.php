<?php

	require_once($_GET['abspath'] . 'wp-load.php');
	require_once($_GET['abspath'] . 'wp-admin/includes/admin.php');
	require_once($_GET['mainPluginFile']);
	require_once(ANNONCES_LIB_PLUGIN_DIR . 'photo/upload.php');

	$result = handleUpload();
	$fichier = tools::IsValid_Variable($result['fichier']);

	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);