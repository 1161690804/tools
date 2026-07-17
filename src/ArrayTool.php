<?php
namespace Wenshuai\Tools;

class ArrayTool
{
    /**
     * 确保输入转换为数组
     */
    public static function ensureArray($value, string $delimiter = ','): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            $arr = $value;
        } elseif (is_string($value) && $delimiter !== '' && strpos($value, $delimiter) !== false) {
            $arr = explode($delimiter, $value);
        } else {
            $arr = [$value];
        }

        // 过滤空字符串、纯空格
        $arr = array_filter($arr, static function ($val) {
            return trim((string)$val) !== '';
        });
        // 去重并重设连续下标
        return array_values(array_unique($arr));
    }

    /**
     * 提取二维数组指定字段，过滤空值并去重
     * @param array $data 二维数组数据集
     * @param string $field 待提取字段名
     * @return array
     */
    public static function extractDistinctColumn(array $data, string $field): array
    {
        // 字段不存在返回null，不参与后续统计
        $values = array_map(static function ($row) use ($field) {
            return $row[$field] ?? null;
        }, $data);

        // 过滤空、空白字符串、null
        $values = array_filter($values, static function ($val) {
            return trim((string)$val) !== '';
        });

        // 去重，重置连续索引
        return array_values(array_unique($values));
    }
}
