<?php
//-------- CATEGORIES --------//

/**
 * GET single album or all albums
 * If you want all albums, pass 'all' instead of an id
 */
$app->get('/album/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);
    
    if ($id === 'all') {
        // Return all albums
        if ($result = $mysqli->query("SELECT * FROM albums")) {
            $response['success'] = TRUE;
            $response['data'] = Array();
            while($row = $result->fetch_object()) {
                $response['data'][] = $row;
            }
        } else {
            $response['error'] = $mysqli->error;
        }
    } else {
        // Return album identified by $id
        if ($result = $mysqli->query("SELECT * FROM albums WHERE album_id = '$id'")) {
            if($row = $result->fetch_object()) {
                $response['success'] = TRUE;
                $response['data'] = $row;
            } else {
                $response['error'] = "no album found matching id $id";
            }
        } else {
            $response['error'] = $mysqli->error;
        }
    }

    $app->response->setBody(json_encode($response));
});

/**
 * PUT album
 */
$app->put('/album/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);
    $body = json_decode($app->request->getBody());

    $album_name = $mysqli->real_escape_string($body->album_name);
    $album_description = $mysqli->real_escape_string($body->album_description);
    $album_date = $mysqli->real_escape_string($body->album_date);

    if ($id !== '-1') {
        $album = $mysqli->query("SELECT * FROM albums WHERE album_id = '$id'")->fetch_object();
        if ($album) {
            // Update album identified by $id
            if ($mysqli->query("UPDATE albums SET album_name = '$album_name', album_description='$album_description', album_date='$album_date' WHERE album_id = '$id';")) {
                $response['success'] = TRUE;
                $response['action'] = 'album updated';
            } else {
                $response['error'] = $mysqli->error;
            }
        } else {
            $response['error'] = "no album found matching id $id";
        }
    } else {
        // Insert album
        if ($mysqli->query("INSERT INTO albums (album_name, album_description, album_date) VALUES('$album_name', '$album_description', '$album_date');")) {
            $response['success'] = TRUE;
            $response['action'] = 'album inserted';
            $response['id'] = $mysqli->insert_id;
            CommonMethods::logAction($mysqli, "Album added", "$album_name");
        } else {
            $response['error'] = $mysqli->error;
            CommonMethods::logAction($mysqli, "Album $album_name could not be added", $mysqli->error);
        }
    }
    $app->response->setBody(json_encode($response));
});

/**
 * DELETE album
 */
$app->delete('/album/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);

    $album = $mysqli->query("SELECT * FROM albums WHERE album_id = '$id'")->fetch_object();
    if ($album) {
        // Delete album identified by $id
        if ($mysqli->query("DELETE FROM albums WHERE album_id = '$id';")) {
            $response['success'] = TRUE;
            $response['action'] = 'album deleted';
            CommonMethods::logAction($mysqli, "Album deleted", "$id");
        } else {
            $response['error'] = $mysqli->error;
            CommonMethods::logAction($mysqli, "Album $id could not be deleted", $mysqli->error);
        }
    } else {
        $response['error'] = "no album found matching id $id";
    }
    $app->response->setBody(json_encode($response));
});