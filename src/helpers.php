<?php

if (! function_exists('objectToArray')) {
    function objectToArray(object $object): array
    {
        return json_decode(json_encode($object), true);
    }
}
