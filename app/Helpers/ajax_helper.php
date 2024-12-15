<?php
if (!function_exists('ajax_csrf')) {
    function ajax_csrf(): array
    {
        return ['result' => 200, 'csrf' => csrf_hash()];
    }
}
