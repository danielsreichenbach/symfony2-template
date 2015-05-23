<?php

if (opcache_reset()) {
    $success = true;
} else {
    $success = false;
}

die(json_encode(array('success' => $success)));
