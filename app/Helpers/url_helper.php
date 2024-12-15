<?php

if (!function_exists('converter_controller')) {
    function converter_controller(string $str, string $separator = '_'): string
    {
        $arr = explode($separator, $str);
        $text = [];
        foreach ($arr as $key => $val)
            $text[] = ucwords($val);
        return implode('', $text) . 'Controller';
    }
}

if (!function_exists('pageNm_html')) {
    function pageNm_html($pageNm = 10): string
    {
        $pageNm_ = [];
        foreach ($_GET as $key => $val) {
            if ($key != 'pageNm' && $key != 'page') {
                if (is_array($val)) {
                    foreach ($val as $v) {
                        $pageNm_[] = $key . '[]=' . $v;
                    }
                } else {
                    $pageNm_[] = $key . '=' . $val;
                }
            }
        }
        $pagging = [10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 400, 500];
        $html = '<select name="pageNm" class="page-select" onChange="javascript:location.href=\'?' . implode('&', $pageNm_) . '&pageNm=\'+this.value">';
        foreach ($pagging as $row) {
            $selected = ($row == $pageNm) ? 'selected' : '';
            $html .= '<option value="' . $row . '" ' . $selected . '>' . $row . '개 보기</option>';
        }
        $html .= '</select>';
        return $html;
    }
}

if (!function_exists('is_login')) {
    function is_login(): bool
    {
        $data = false;
        $session = session();
        if ($session->has('is_user') && $session->is_user === true) $data = true;
        return $data;
    }
}

if (!function_exists('admin_auth')) {
    function admin_auth(string $code, string $enum = null): string
    {
        $data = '';
        if (!isset($enum) || $enum == 'y')
            switch ($code) {
                case 'all':
                    $data = '전체';
                    break;
                case 'A':
                    $data = '관리자';
                    break;
                case 'L':
                    $data = '라벨러';
                    break;
                case 'M':
                    $data = '맴버';
                    break;
                case 'C':
                    $data = '맴버';
                    break;
            }
        else $data = '사용안함';
        return $data;
    }
}

if (!function_exists('base64url_encode')) {
    function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

if (!function_exists('base64url_decode')) {
    function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
