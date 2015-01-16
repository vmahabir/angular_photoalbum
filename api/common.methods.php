<?php

abstract class CommonMethods {

	static function deletePhoto($id, $mysqli, $response) {
		$id = $mysqli->real_escape_string($id);

	    $photo = $mysqli->query("SELECT * FROM photos WHERE photo_id = '$id'")->fetch_object();
	    if ($photo) {
	        // Delete the file!
	        $filepath = Config::UPLOAD_DIR."/".$photo->photo_filename;
	        if (file_exists($filepath)) {
	            unlink($filepath);
	        }
	        // Delete photo identified by $id
	        if ($mysqli->query("DELETE FROM photos WHERE photo_id = '$id';")) {
	            $response['success'] = TRUE;
	            $response['action'] = 'photo deleted';
	        } else {
                CommonMethods::logAction($mysqli, "Delete photo failed", $mysqli->error);
                $response['error'] = $mysqli->error;
	        }
	    } else {
	        $response['error'] = "no photo found matching id $id";
	    }
	}

    static function logAction($mysqli, $action, $information) {
        $username = null;
        $action = $mysqli->real_escape_string($action);
        $information = $mysqli->real_escape_string($information);

        if (isset($_COOKIE['authorised-username'])) {
            $username = $mysqli->real_escape_string($_COOKIE['authorised-username']);
        }

        try {
            $mysqli->query("INSERT INTO logs (action, info, logged_in_as) VALUES('$action', '$information', '$username');");
        } catch(Exception $e) {}
    }
}