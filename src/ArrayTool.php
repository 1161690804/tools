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
    public static function uniqueFieldValues(array $data, string $field): array
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

    /**
     * 解析逗号分隔ID字符串，返回去重、过滤0的整型ID数组
     * 兼容null、空字符串、重复ID、非数字脏数据
     * @param string|null $idStr 逗号拼接ID字符串
     * @return int[]
     */
    public static function uniqueIdsFromCommaStr(string $idStr = ''): array
    {
        $arr = explode(',', $idStr);
        $intArr = array_map('intval', $arr);
        $filterArr = array_filter($intArr);
        $uniqueArr = array_unique($filterArr);
        return array_values($uniqueArr);
    }

    /**
     * 按指定字段拼接下划线复合键重新索引数组
     * @param array $orig 源二维数组
     * @param string|array $columns 拼接key的字段（仅单层，不支持回调）
     * @param bool $isArray true=同key聚合数组 false=覆盖单条
     * @param string|null|callable $valueRule
     *      null：整条记录作为value；
     *      字符串：取该行对应字段作为value；
     *      回调函数：自定义返回value，参数($record)
     * @return array
     */
    public static function reindexArray(array $orig, $columns, bool $isArray = false, $valueRule = null): array
    {
        $result = [];
        if (empty($orig)) {
            return $result;
        }

        $columns = is_array($columns) ? $columns : [$columns];

        foreach ($orig as $record) {
            // 生成复合key，沿用你原始逻辑，无回调
            $key = '';
            foreach ($columns as $col) {
                $val = $record[$col] ?? '0';
                $key .= $val . '_';
            }
            $key = rtrim($key, '_');

            // 处理value
            if (is_callable($valueRule)) {
                // 回调：自由加工value，适配你goods_id=>[group_id]场景
                $itemValue = $valueRule($record);
            } elseif (is_string($valueRule)) {
                // 传字段名，直接取字段值
                $itemValue = $record[$valueRule] ?? null;
            } else {
                // 默认：整条行数据
                $itemValue = $record;
            }

            if ($isArray) {
                $result[$key][] = $itemValue;
            } else {
                $result[$key] = $itemValue;
            }
        }

        return $result;
    }

}
