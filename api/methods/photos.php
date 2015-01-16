<?php
//-------- PHOTOS --------//
//////////////////////////////////////////////////////////////////////
// CONFIG
////////////////////////////////////////////////////////////////////

/**
 * GET photo
 */
$app->get('/photo/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);

    // Return photo identified by $id
    if ($result = $mysqli->query("SELECT * FROM photos WHERE photo_id = '$id'")) {
        if($row = $result->fetch_object()) {
            $response['success'] = TRUE;
            $response['data'] = $row;
        } else {
            $response['error'] = "no photo found matching id $id";
        }
    } else {
        $response['error'] = $mysqli->error;
    }
    $app->response->setBody(json_encode($response));
});

/**
 * GET photos by album
 */
$app->get('/photosbyalbum/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);

    // Return photo identified by $id
    if ($result = $mysqli->query("SELECT * FROM photos WHERE photo_album_id = '$id'")) {
        $response['success'] = TRUE;
        $response['data'] = Array();
        while($row = $result->fetch_object()) {
            $response['data'][] = $row;
        }
    } else {
        $response['error'] = $mysqli->error;
    }
    $app->response->setBody(json_encode($response));
});

/**
 * PUT photo
 */
$app->put('/photo/:id', function ($id) use ($app, $mysqli, $response) {
    $id = $mysqli->real_escape_string($id);
    $body = json_decode($app->request->getBody());
    
    $photo_id = $mysqli->real_escape_string($body->photo_id);
    $photo_album_id = $mysqli->real_escape_string($body->photo_album_id);
    $photo_filename = $mysqli->real_escape_string($body->photo_filename);
    $photo_camera = $mysqli->real_escape_string($body->photo_camera);
    $photo_lens = $mysqli->real_escape_string($body->photo_lens);
    $photo_filetype = $mysqli->real_escape_string($body->photo_filetype);
    $photo_date = $mysqli->real_escape_string($body->photo_date);
    $photo_fstop = $mysqli->real_escape_string($body->photo_fstop);
    $photo_flash = $mysqli->real_escape_string($body->photo_flash);
    $photo_iso = $mysqli->real_escape_string($body->photo_iso);
    $photo_focal_length = $mysqli->real_escape_string($body->photo_focal_length);
    $photo_shutter = $mysqli->real_escape_string($body->photo_shutter);

    if ($id !== '-1') {
        $photo = $mysqli->query("SELECT * FROM photos WHERE photo_id = '$id'")->fetch_object();
        if ($photo) {
            // Update photo identified by $id
            if ($mysqli->query("UPDATE photos SET photo_id='$photo_id', photo_album_id='$photo_album_id', photo_filename='$photo_filename', photo_camera='$photo_camera', photo_lens='$photo_lens', photo_filetype='$photo_filetype', photo_date='$photo_date', photo_fstop='$photo_fstop', photo_flash='$photo_flash', photo_iso='$photo_iso', photo_focal_length='$photo_focal_length', photo_shutter='$photo_shutter' WHERE photo_id = '$id';")) {
                $response['success'] = TRUE;
                $response['action'] = 'photo updated';
            } else {
                $response['error'] = $mysqli->error;
            }
        } else {
            $response['error'] = "no photo found matching id $id";
        }
    } else {
        // Insert photo
        if ($mysqli->query("INSERT INTO photos (photo_id, photo_album_id, photo_filename, photo_camera, photo_lens, photo_filetype, photo_date, photo_fstop, photo_flash, photo_iso, photo_focal_length, photo_shutter) VALUES('$photo_id', '$photo_album_id', ''$photo_filename','$photo_camera', ''$photo_lens', '$photo_filetype', ''$photo_date','$photo_fstop', ''$photo_flash', '$photo_iso', ''$photo_focal_length','$photo_shutter',);")) {
            $response['success'] = TRUE;
            $response['action'] = 'photo inserted';
            $response['id'] = $mysqli->insert_id;
        } else {
            $response['error'] = $mysqli->error;
            CommonMethods::logAction($mysqli, "Add photo failed", $mysqli->error);
        }
    }
    $app->response->setBody(json_encode($response));
});

/**
 * DELETE photo
 */
$app->delete('/photo/:id', function ($id) use ($app, $mysqli, $response) {
    CommonMethods::deletePhoto($id, $mysqli, $response);
    $app->response->setBody(json_encode($response));
});