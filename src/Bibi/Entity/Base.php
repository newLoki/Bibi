<?php

namespace Bibi\Entity;

abstract class Base
{
    const DATE_FORMAT = 'Y-m-d\TH:i:s';

    public function getSimpleObject()
    {
        $result = new \stdClass();

        foreach (get_class_vars(get_class($this)) as $key => $value) {
            $methodName = "get" . ucfirst($key);
            $result->{$key} = call_user_func(array($this, $methodName));
        }

        return $result;
    }
}