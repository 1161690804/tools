<?php
namespace Wenshuai\Tools;

class AmountTool
{

    public function index()
    {
        echo 1;exit;
    }

    public static function calculateDiscountPercentage(string $guideAmount, string $payAmount, $multiplier = 10 ): string
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

}
