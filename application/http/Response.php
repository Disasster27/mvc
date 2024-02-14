<?php

namespace application\http;


namespace application\http;


class Response
{
    public function setHeader($name, $value)
    {
        header("$name: $value");
    }

}