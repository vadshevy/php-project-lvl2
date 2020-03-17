<?php

namespace Gendiff\gendiff;

use function Gendiff\render\render;

function gendiff($file1, $file2)
{
//    $before = $opts->args["<firstFile>"];
//    if (file_exists($before)) {
    $data1 = file_get_contents($file1, true);
    $beforeContent = json_decode($data1, $assoc = true);
//    }
//    $after = $opts->args["<secondFile>"];
//    if (file_exists($after)) {
    $data2 = file_get_contents($file2, true);
    $afterContent = json_decode($data2, $assoc = true);
//    }
    $merged = array_merge($beforeContent, $afterContent);
    $result = [];
    $callback = function ($value, $key) use ($beforeContent, $afterContent, &$result) {
        $bothKeysExist = array_key_exists($key, $beforeContent) && array_key_exists($key, $afterContent);
        if ($bothKeysExist && $value === $beforeContent[$key] && $value === $afterContent[$key]) {
            $result[] = ['key' => $key, 'value' => $value, 'state' => ''];
        }
        if ($bothKeysExist && $value !== $beforeContent[$key] && $value === $afterContent[$key]) {
            $result[] = ['key' => $key, 'value' => $beforeContent[$key], 'state' => '-'];
        }
        if (array_key_exists($key, $beforeContent) && $value !== $beforeContent[$key]) {
            $result[] = ['key' => $key, 'value' => $value, 'state' => '+'];
        }
        if (!array_key_exists($key, $beforeContent)) {
            $result[] = ['key' => $key, 'value' => $value, 'state' => '+'];
        }
        if (!array_key_exists($key, $afterContent)) {
            $result[] = ['key' => $key, 'value' => $value, 'state' => '-'];
        }
        return $result;
    };
    array_walk($merged, $callback);
    return($result);
}
