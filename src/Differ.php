<?php

namespace Gen\Diff\Differ;

function getSortedUniqueKeys($coll1, $coll2)
{
    $allKeys = array_merge(array_keys($coll1), array_keys($coll2));
    $uniqueKeys = array_unique($allKeys);
    natsort($uniqueKeys);
    return array_values($uniqueKeys);
}

function isKeyAdded($key, $coll1, $coll2)
{
    return !array_key_exists($key, $coll1)
        && array_key_exists($key, $coll2);
}

function isKeyRemoved($key, $coll1, $coll2)
{
    return array_key_exists($key, $coll1)
        && !array_key_exists($key, $coll2);
}

function isValueChanged($key, $coll1, $coll2)
{
    return $coll1[$key] !== $coll2[$key];
}

function checkKey($key, $coll1, $coll2)
{
    $difference = [];
    if (isKeyAdded($key, $coll1, $coll2)) {
        $difference["+ {$key}"] = $coll2[$key];
    } elseif (isKeyRemoved($key, $coll1, $coll2)) {
        $difference["- {$key}"] = $coll1[$key];
    } elseif (isValueChanged($key, $coll1, $coll2)) {
        $difference["- {$key}"] = $coll1[$key];
        $difference["+ {$key}"] = $coll2[$key];
    } else {
        $difference["  {$key}"] = $coll2[$key];
    }

    return $difference;
}

function genDiff(string $filePath1, string $filePath2): string
{
    $fileData1 = file_get_contents($filePath1);
    $fileData2 = file_get_contents($filePath2);
    $jsonData1 = json_decode($fileData1, true);
    $jsonData2 = json_decode($fileData2, true);
    $dataKeys = getSortedUniqueKeys($jsonData1, $jsonData2);
    $difference = array_reduce($dataKeys, function ($acc, $key) use ($jsonData1, $jsonData2) {
        $acc = array_merge($acc, checkKey($key, $jsonData1, $jsonData2));
        return $acc;
    }, []);
    $diffText = json_encode($difference, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    $finalDiffText = str_replace('"', '', $diffText);
    return $finalDiffText . PHP_EOL;
}
