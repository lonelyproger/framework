<?php

namespace Lonelyproger\Framework\Core;

class Render
{
    public function __construct($template)
    {
        $template_content = file_get_contents('/tpls/' . $template . '.tpl');
    }
}
