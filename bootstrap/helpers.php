<?php

function route_class(){
    return str_replace('.', '-', Route::currentRouteName());
}

function ddd($data) {
    echo "<pre>";

    print_r ($data);

    echo "</pre>";
}