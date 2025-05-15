<?php

namespace Lonelyproger\Framework\Core;

use function Lonelyproger\Framework\Core\between;

class Render
{
    public $template_file = '';
    public $template_content = '';
    public $template_dir = '../tpls/';
    public $extend = false;
    public $options;
    public function __construct() {}
    public function render($template, $options = [])
    {
        $this->options = $options;
        $this->template_file = $this->template_dir . $template . '.tpl';
        $this->template_content = file_get_contents($this->template_file);
        do {
            $pos = strpos($this->template_content, '@@@', $pos ?? 0);
//            var_dump(is_int($pos));
            if (is_int($pos))
                $ops[$pos] = substr($this->template_content, $pos, strpos($this->template_content, PHP_EOL, $pos) - $pos);
        } while ($pos);
//        var_dump($ops);
//        exit;
        $first_line = preg_split('#\r?\n#', ltrim($this->template_content), 2)[0];
        if (str_starts_with($first_line, '@extend')) {
            $this->extend = file_get_contents($this->template_dir . explode(' ', $first_line)[1] . '.tpl');
            $this->template_content = str_replace($first_line, '', $this->template_content);
            $this->template_content = str_replace('@content', $this->template_content, $this->extend);
            $title = between($this->template_content, '<h1>', '</h1>');
            $this->template_content = str_replace('@title', $title[0], $this->template_content);
        }
        $this->include($this->template_content);
        if (file_exists('js/' . $template . '.js'))
            $this->options['js'][] = $template;
        if (array_key_exists('js', $this->options)) {
            foreach ($this->options['js'] as $key => $value) {
                $js[] = "<script src=\"js/{$value}.js\"></script>";
            }
            $this->template_content = str_replace('@js', implode("\n", $js), $this->template_content);
        }
        foreach ($options as $key => $value) {
            if ($key === 'js') continue;
            if (!is_array($value)) {
                $this->template_content = str_replace('{{' . $key . '}}', $value, $this->template_content);
            } elseif (is_array($value)) {
                $start_for = strpos($this->template_content, '@for ' . $key);
                $end_for = strpos($this->template_content, '@endfor', $start_for);
                $op = substr($this->template_content, $start_for, strpos($this->template_content, PHP_EOL, $start_for) - $start_for);
                $as_vars = preg_split('/\s+/', $op);
                $as_vars = explode(',', array_pop($as_vars));
                $tmpl_start = $start_for + strlen($op);
                $tmpl = substr($this->template_content, $tmpl_start, $end_for - $tmpl_start);
                $as_vars_ind = between($tmpl, '{{' . $as_vars[1] . '[', ']}}');
                $res_template = '';
                foreach ($value as $k => $v) {
                    if (count($as_vars) > 1) {
                        $res_tmpl = str_replace("{{{$as_vars[0]}}}", $k, $tmpl);
                        if (is_array($v)) {
                            $arr_str = '';
                            foreach ($v as $vkey => $vvalue) {
                                $arr_str .= $vkey . ' => ' . $vvalue . ', ';
                                $res_tmpl = str_replace("{{{$as_vars[1]}[{$vkey}]}}", $vvalue ?? 'Итого', $res_tmpl);
                            }
                            $arr_str = rtrim($arr_str, ', ');
                            $res_tmpl = str_replace("{{{$as_vars[1]}}}", $arr_str, $res_tmpl);
                        } else
                            $res_tmpl = str_replace("{{{$as_vars[1]}}}", $v, $res_tmpl);
                    }
                    $res_template .= $res_tmpl;
                }
                $this->template_content = str_replace($op . $tmpl . '@endfor', $res_template, $this->template_content);
            }
        }
        return $this->template_content;
    }
    private function include(&$haystack)
    {
        while ($pos = strpos($haystack, '@include')) {
            $op = substr($haystack, $pos, (strpos($haystack, PHP_EOL, $pos)) - $pos);
            $inc = file_get_contents($this->template_dir . explode(' ', $op)[1] . '.tpl');
            $haystack = str_replace($op, $inc, $haystack);
        }
    }
    public static function view($template, $options = [])
    {
        return (new self)->render(strval($template), $options);
    }
}
