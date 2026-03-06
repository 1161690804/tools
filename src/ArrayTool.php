<?php
namespace Wenshuai\Tools;

class ArrayTool
{
    /**
     * 确保输入转换为数组
     */
    function ensureArray($value, string $delimiter = ','): array
    {
        if(empty($value)) {
            return [];
        }

        if(is_array($value)) {
            return $value;
        }

        if (is_string($value) && $delimiter !== '' && strpos($value, $delimiter) !== false) {
            return explode($delimiter, $value);
        }

        return [$value];
    }
}
