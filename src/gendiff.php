<<<<<<< 2f84d188d3ed36ff78440dfbce439adb07bc2604
<?php

namespace Gendiff\gendiff;

use function Gendiff\render\render;

function gendiff($opts)
{
    $before = $opts->args["<firstFile>"];
//    if (file_exists($before)) {
    $file1 = file_get_contents($before, true);
    $beforeContent = json_decode($file1, $assoc = true);
//    }
    $after = $opts->args["<secondFile>"];
//    if (file_exists($after)) {
    $file2 = file_get_contents($after, true);
    $afterContent = json_decode($file2, $assoc = true);
//    }
    $merged = array_merge($beforeContent, $afterContent);
    $result = [];
    $callback = function ($value, $key) use ($beforeContent, $afterContent, &$result) {
        if (array_key_exists($key, $beforeContent) && array_key_exists($key, $afterContent) && $value === $beforeContent[$key] && $value === $afterContent[$key]) {
            $result[] = ['key' => $key, 'value' => $value, 'state' => ' '];
        }
        if (array_key_exists($key, $beforeContent) && array_key_exists($key, $afterContent) && $value !== $beforeContent[$key] && $value === $afterContent[$key]) {
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
=======
<?php

namespace Gendiff\gendiff;

function gendiff($opts)
{
    $before = $opts["<firstFile>"];
    if (file_exists($before)) {
    $beforeContent = json_decode(file_get_contents($before), $assoc = true);    
    }

    $after = $opts["<secondFile>"];
    if (file_exists($after)) {
    $afterContent = json_decode(file_get_contents($after), $assoc = true);    
    }

    $result = [];

    foreach ($afterContent as $key => $value) {
        
        if (array_key_exists($key, $beforeContent) && $value === $beforeContent[$key]) {
            $result[] = "  {$key}:{$value}";
        } elseif (array_key_exists($key, $beforeContent) && $value != $beforeContent[$key]) {
            $result[] = "+ {$key}:{$value}";
            $result[] = "- {$key}: {$beforeContent[$key]}";
        } elseif (!array_key_exists($key, $beforeContent)) {
            $result[] = "+ {$key}:{$value}";
        }
    }
    
    foreach ($beforeContent as $key => $value) {
        if (!array_key_exists($key, $afterContent)) {
            $result[] = "+ {$key}:{$value}";
        }
    }
    return $result;
}
>>>>>>> step 3 before refactoring
