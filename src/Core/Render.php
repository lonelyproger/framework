<?php

namespace Lonelyproger\Framework\Core;

class Render
{
    public $template_file = '';
    public $template_content = '';
    public $template_dir = '../tpls/';
    public $extend = false;
    public $options;
    public function __construct($template, $options = [])
    {
        $this->options = $options;
        $this->template_file = $this->template_dir . $template . '.tpl';
        $this->template_content = file_get_contents($this->template_file);
    }
    public function render()
    {
        $first_line = preg_split('#\r?\n#', ltrim($this->template_content), 2)[0];
        if (str_starts_with($first_line, '@extend')) {
            $this->extend = file_get_contents($this->template_dir . explode(' ', $first_line)[1] . '.tpl');
            $this->template_content = str_replace($first_line, '', $this->template_content);
        }
        if ($this->extend) {
            while ($pos = strpos($this->extend, '@include')) {
                $op = substr($this->extend, $pos, (strpos($this->extend, PHP_EOL, $pos)) - $pos);
                $inc = file_get_contents($this->template_dir . explode(' ', $op)[1] . '.tpl');
                $this->extend = str_replace($op, $inc, $this->extend);
            }
            $this->extend = str_replace('@content', $this->template_content, $this->extend);
            return $this->extend;
        } else {
            return $this->template_content;
        }
    }
}
