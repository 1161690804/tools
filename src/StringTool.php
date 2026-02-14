<?php
namespace Wenshuai\Tools;

class StringTool
{

    /**
     * Notes: 生成单个随机数
     * @param int $length
     * @param bool $includeLowercase
     * @return string
     * @author wenshuai 2026/2/13 15:25
     */
    public static function generateCode(int $length = 4, bool $includeLowercase = false): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($includeLowercase) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }

        $result = '';
        $charCount = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $bytes = random_bytes(1);
            $index = hexdec(bin2hex($bytes)) % $charCount;
            $result .= $characters[$index];
        }

        return $result;
    }

    /**
     * 生成多个不重复的随机字符串
     * @param int $count 需要生成的数量
     * @param int $length 每个字符串的长度
     * @param bool $includeLowercase 是否包含小写字母
     * @return array
     */
    public static function generateMultipleCodes(int $count, int $length = 4, bool $includeLowercase = false): array
    {
        $codes = [];
        $maxAttempts = $count * 100; // 最大尝试次数防止无限循环

        for ($i = 0; $i < $maxAttempts && count($codes) < $count; $i++) {
            $code = self::generateCode($length, $includeLowercase);
            if (!in_array($code, $codes)) {
                $codes[] = $code;
            }
        }

        return $codes;
    }
}
