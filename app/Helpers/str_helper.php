<?php

if (!function_exists('mask')) {
    function mask(string $str, string $first, string $last): string
    {
        $len = strlen($str);
        $toShow = $first + $last;
        return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
    }
}

if (!function_exists('mask_email')) {
    function mask_email(string $str): string
    {
        $mail_parts = explode("@", $str);
        $domain_parts = explode('.', $mail_parts[1]);
        $mail_parts[0] = mask($mail_parts[0], 3, 2); // show first 2 letters and last 1 letter
        $domain_parts[0] = mask($domain_parts[0], 2, 1);
        $mail_parts[1] = implode('.', $domain_parts);
        return implode("@", $mail_parts);
    }
}
