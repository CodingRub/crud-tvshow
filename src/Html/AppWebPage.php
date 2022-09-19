<?php

namespace Html;

class AppWebPage extends WebPage
{
    public function __construct(string $title = "")
    {
        parent::__construct($title);
        $this->appendCssUrl('/css/style.css');
    }

    public function toHTML(): string
    {
        return parent::toHTML();
    }
}
