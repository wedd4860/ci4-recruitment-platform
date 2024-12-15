<?php

namespace App\Models;

use CodeIgniter\Controller;

class Converter extends Controller
{
    private $arr;
    public function __construct($arr = null)
    {
        $this->arr = $arr;
    }

    public function setArr(array $arr)
    {
        $this->arr = $arr;
        return $this;
    }

    public function clear()
    {
        $this->arr = null;
        return $this;
    }

    public function arrToRow()
    {
        helper('text');
        foreach ($this->arr as $key => $val)
            $keys[] = $key;
        $max_ = count($this->arr[$keys[0]]);
        for ($i = 0; $i < $max_; $i++)
            foreach ($keys as $row)
                $data[$i][$row] = reduce_multiples($this->arr[$row][$i], ',', TRUE);
        return $data;
    }

    public function setInput(string $input_name = null, array $value = [])
    {
        $data = [];
        $script = [];
        $editor_use = false;
        foreach ($this->arr as $key => $val) {
            $data[$key]['name'] = $val['name'];
            $pattern = ($val['option'] == 'none') ? '' : ' data-pattern="' . $val['option'] . '" class="' . $val['option'] . '" ';
            $required = ($val['required'] == 'n') ? '' : ' data-required="true" data-name="' . $val['name'] . '" ';
            $input_name_ = $input_name ? $input_name . '[' . $val['code'] . ']' : $val['code'];
            $input_value = $value[$val['code']] ?? '';
            switch ($val['type']) {
                case 'text':
                case 'password':
                    $data[$key]['tag'] = '<input name="' . $input_name_ . '" type="' . $val['type'] . '" ' . $required . $pattern . ' value="' . $input_value . '">';
                    break;
                case 'textarea':
                    $data[$key]['tag'] = '<textarea name="' . $input_name_ . '" ' . $required . $pattern . '>' . esc($input_value) . '</textarea>';
                    break;
                case 'editor':
                    $data[$key]['tag'] = '<textarea id="editor-' . $val['code'] . '-' . $key . '" name="' . $input_name_ . '" ' . $required . $pattern . '>' . esc($input_value) . '</textarea>';
                    $use_photh = ($val['use_photo'] == 'y') ? ',filebrowserUploadUrl : "/editor-upload",fileTools_requestHeaders: {"X-CSRF-TOKEN": $("meta[name=\'X-CSRF-TOKEN\']").attr("content")}' : '';
                    $script[] = 'CKEDITOR.replace("editor-' . $val['code'] . '-' . $key . '",{customConfig: "/public/ckeditor/config.js" ' . $use_photh . ' });';
                    // $script[] = 'CKEDITOR.instances[\'editor-'.$val['code'].'-'.$key.'\'].setData(\''.esc($input_value).'\');';
                    // CKEDITOR.instances['textareaId'].setData(value);
                    $editor_use = true;
                    break;
                case 'radio':
                case 'checkbox':
                    $data[$key]['tag'] = $this->setInputTag($input_name_, explode(',', $val['value']), $val['type'], $val['code'], $input_value);
                    break;
                case 'select':
                    $data[$key]['tag'] = $this->setSelectTag($input_name_, explode(',', $val['value']), $val['code'], $input_value);
                    break;
                case 'file':
                    $val['file_ea'] = ($val['file_ea'] != '') ? $val['file_ea'] : 999;
                    $accept = $this->getFileAccept(explode(',', $val['file_type']));
                    $data[$key]['name'] .= ($val['file_ea'] > 1) ? '<label class="add-file" onClick="add_file(this)" data-id="td-' . $val['code'] . '-' . $key . '" data-name="' . $input_name_ . '" data-code="' . $val['code'] . '" data-filemax="' . $val['file_ea'] . '" data-accept="' . $accept . '" ></label>' : '';
                    $data[$key]['tag'] = $this->setFileTag($input_name_, $val['code'], $key, $accept, $input_value);
                    $data[$key]['file_id'] = ' id="td-' . $val['code'] . '-' . $key . '" ';
                    break;
                case 'password_ck':
                    $data[$key]['tag'] = '<input type="password" ' . $required . $pattern . ' data-type="password_ck" >';
                    break;
                case 'address':
                    $data[$key]['tag'] = $this->setAddressTag($input_name_, $val['code'], $key, $input_value);
                    break;
            }
        }
        if ($editor_use)
            $script[] = 'CKEDITOR.on("dialogDefinition", function(ev){editor_img_chek(ev);});';
        return ['data' => $data, 'script' => $script];
    }
    public function setAddressTag($input_name, $code, $key, $input_value)
    {
        $post = is_array($input_value) ? $input_value['post'] : '';
        $address = is_array($input_value) ? $input_value['address'] : '';
        $add_tail = is_array($input_value) ? $input_value['add_tail'] : '';
        $data = '<input name="' . $input_name . '[post]" type="text" value="' . $post . '" style="width: 100px;" id="' . $code . '-' . $key . '" onClick="set_post(this)" data-post="' . $input_name . '[post]" data-add="' . $input_name . '[address]" data-focus="' . $input_name . '[add_tail]" readonly>';
        $data .= '<label for="' . $code . '-' . $key . '" class="btn btn-gray btn-md" style="margin-left: -3px;" >우편번호검색</label><br />';
        $data .= '<input name="' . $input_name . '[address]" type="text" value="' . $address . '" readonly>';
        $data .= '<input name="' . $input_name . '[add_tail]" type="text" value="' . $add_tail . '" style="width: 150px;">';
        return $data;
    }
    public function getFileAccept(array $accept)
    {
        $ext = [];
        foreach ($accept as $row) if ($row) $ext[] = '.' . $row;
        return count($ext) ? implode(',', $ext) : '';
    }
    public function setFileTag($input_name, $code, $key, $accept = null, $input_value)
    {
        $data = '';
        $accept = $accept ? 'accept="' . $accept . '"' : '';
        if (is_array($input_value)) {
            // print_r($input_value);
            foreach ($input_value as $key_ => $val) {
                $file = new \CodeIgniter\Files\File($_SERVER['DOCUMENT_ROOT'] . $val['path']);
                $file_type = explode('/', $file->getMimeType());
                $src = ($file_type[0] == 'image') ? $val['path'] : '';
                $data .= '<div id="f-div-' . $key . $key_ . '" class="file-warp-div">';

                foreach ($val as $k => $v) {
                    $data .= '<input name="' . $input_name . '[' . $key_ . '][' . $k . ']" type="hidden" value="' . $v . '">';
                }

                $data .= '<img src="' . $src . '" style="height:30px; margin-right:2px" onError="no_image(this)" id="preview-' . $code . '-' . $key . $key_ . '">';
                $data .= '<input type="file" id="' . $code . '-' . $key . $key_ . '" name="' . $input_name . '[]" class="hide" onChange="upload_file(this)" data-preview="preview-' . $code . '-' . $key . $key_ . '" ' . $accept . '>';
                $data .= '<label for="' . $code . '-' . $key . $key_ . '" type="button" class="btn-file"></label>';
                $data .= ($key_ != 0) ? '<label class="del-file-label" onClick="del_file(\'' . $key . $key_ . '\')"></label>' : '';
                $data .= '<span style="margin-left:15px">' . $val['fileNm'] . '</span>';
                $data .= '<div>';
            }
        } else {
            $data = '<img src="" style="height:30px; margin-right:2px" onError="no_image(this)" id="preview-' . $code . '-' . $key . '">';
            $data .= '<input type="file" id="' . $code . '-' . $key . '" name="' . $input_name . '[]" class="hide" onChange="upload_file(this)" data-preview="preview-' . $code . '-' . $key . '" ' . $accept . '>';
            $data .= '<label for="' . $code . '-' . $key . '" type="button" class="btn-file"></label>';
        }
        return $data;
    }

    public function setSelectTag($input_name, array $var, $code, $input_value)
    {
        $data = '<select name="' . $input_name . '">';
        $max_ = count($var);
        for ($i = 0; $i < $max_; $i++) {
            $selected = ($input_value != '' && $input_value == $var[$i]) ? ' selected ' : '';
            $data .= '<option value="' . $var[$i] . '"' . $selected . '>' . $var[$i] . '</option>';
        }
        return $data . '</select>';
    }

    public function setInputTag($input_name, array $var, $type, $code, $input_value)
    {
        $data = '';
        $max_ = count($var);
        for ($i = 0; $i < $max_; $i++) {
            $name = ($type == 'radio') ? $input_name : $input_name . '[]';
            if (is_array($input_value)) {
                $checked = (in_array($var[$i], $input_value)) ? 'checked' : '';
            } else {
                if ($input_value != '') {
                    $checked = ($var[$i] == $input_value) ? 'checked' : '';
                } else {
                    $checked = ($type == 'radio' && $i == 0) ? 'checked' : '';
                }
            }
            $id = $type . '-' . $code . '-' . $i;
            $data .= '<input name="' . $name . '" type="' . $type . '" value="' . $var[$i] . '" id="' . $id . '" ' . $checked . '><label for="' . $id . '">' . $var[$i] . '</label>';
        }
        return $data;
    }

    public static function getListText($val, $type, $data = '', bool $filter = true)
    {
        helper('text');
        switch ($type) {
            case 'text':
            case 'select':
            case 'radio':
                $data = ($filter) ? ellipsize(esc($val), 120, 1) : esc($val);
                break;
            case 'checkbox':
                $data = (is_array($val)) ? $data = implode(', ', $val) : $val;
                break;
            case 'textarea':
                $data = ($filter) ? ellipsize(esc($val), 30, .5) : esc($val);
                break;
            case 'editor':
                $data = ($filter) ? ellipsize($val, 30, .5) : $val;
                break;
            case 'address':
                $data = '[' . esc($val['post']) . '] ' . esc($val['address']) . ' ' . esc($val['add_tail']);
                break;
            case 'file':
                $data = '';
                if (is_array($val)) {
                    if ($filter === true) {
                        foreach ($val as $row)
                            $data_[] = esc($row['fileNm']);
                        $data = implode(', ', $data_);
                    } else {
                        foreach ($val as $row)
                            $data_[] = '<a class="file-down" href="/file-down?from=' . $row['path'] . '&to=' . $row['fileNm'] . '">' . $row['fileNm'] . '</a>';
                        $data = implode(', ', $data_);
                    }
                }
                break;
            case 'password':
                $data = '*********';
                break;
        }
        return $data;
    }
}
