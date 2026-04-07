<?php
namespace Wenshuai\Tools;

class AmountTool
{

    /**
     * Notes: 计算折扣
     * @param string $guideAmount  指导价
     * @param string $payAmount    销售价
     * @param int $multiplier      返回格式
     * @return string
     * @author wenshuai 2026/4/7 10:32
     */
    public static function calculateDiscountPercentage(string $guideAmount, string $payAmount, int $multiplier = 10 ): string
    {
        $multiplierConfig = [
            1 => 2,
            10 => 1,
        ];

        // 原价为0, 无论售价多少，都视为1折
        if(bccomp($guideAmount, '0', 2) == 0) {
            $return =  1;
        }elseif (bccomp($payAmount, '0', 2) === 0) {
            // 支付金额为0，0折
            $return = 0;
        } elseif (bccomp($payAmount, $guideAmount, 2) >= 0) {
            // 支付金额大于指导价、不打折计算
            $return = 1;
        } else {
            $return = bcdiv($payAmount, $guideAmount, 2);
        }

        return bcmul($return, $multiplier, $multiplierConfig[$multiplier]);
    }

    /**
     * Notes: 计算毛利率
     * 毛利率 = (销售收入 - 销售成本) / 销售收入 * 100%
     *
     * @param string $salesRevenue 销售收入
     * @param string $costOfSales 销售成本
     * @param int $scale 保留小数位数
     * @return array|string
     * @author wenshuai 2026/4/7
     */
    public static function calculateGrossProfitRate(string $salesRevenue, string $costOfSales, int $scale = 2)
    {
        $zero = '0';
        $return = [
            'gross_profit' => '0.00',
            'rate' => '0.00%',
        ];

        // 1. 销售收入为0，无法计算毛利率
        if (bccomp($salesRevenue, $zero, 4) === 0) {
            return $return;
        }

        // 2. 计算毛利额
        $return['gross_profit'] = $grossProfit = bcsub($salesRevenue, $costOfSales, 2);

        // 3. 计算毛利率：(毛利额 / 销售收入) * 100
        $return['rate'] = bcmul(
            bcdiv($grossProfit, $salesRevenue, 8),  // 保留8位小数确保精度
            '100',
            $scale
        );

        return $return;
    }

    /**
     * Notes: 标准化数字字符串
     * @param string $number
     * @return string
     */
    private static function normalizeNumber(string $number): string
    {
        // 移除千分位分隔符
        $number = str_replace(',', '', $number);

        // 去除前后空格
        $number = trim($number);

        // 确保是有效的数字
        if (!is_numeric($number)) {
            return '0';
        }

        return $number;
    }




}
