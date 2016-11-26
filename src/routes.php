<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return $response->withRedirect('/auth/login', 301);
});
    