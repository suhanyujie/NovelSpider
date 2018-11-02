<?php
/**
 * 关于数组转换的 辅助函数
 */
namespace Tool\Helper;


class NumberTransfer {

    public static function checkNatInt($str){
        $map = array(
            '一' => '1','二' => '2','三' => '3','四' => '4','五' => '5','六' => '6','七' => '7','八' => '8','九' => '9',
            '壹' => '1','贰' => '2','叁' => '3','肆' => '4','伍' => '5','陆' => '6','柒' => '7','捌' => '8','玖' => '9',
            '零' => '0','两' => '2',
            '仟' => '千','佰' => '百','拾' => '十',
            '万万' => '亿',
        );

        $str = str_replace(array_keys($map), array_values($map), $str);
        $str = self::checkString($str, '/([\d亿万千百十]+)/u');

        $func_c2i = function ($str, $plus = false) use (&$func_c2i) {
            if(false === $plus) {
                $plus = array('亿' => 100000000,'万' => 10000,'千' => 1000,'百' => 100,'十' => 10,);
            }

            $i = 0;
            if($plus)
                foreach($plus as $k => $v) {
                    $i++;
                    if(strpos($str, $k) !== false) {
                        $ex = explode($k, $str, 2);
                        $new_plus = array_slice($plus, $i, null, true);
                        $l = $func_c2i($ex[0], $new_plus);
                        $r = $func_c2i($ex[1], $new_plus);
                        if($l == 0) $l = 1;
                        return $l * $v + $r;
                    }
                }

            return (int)$str;
        };

        return $func_c2i($str);
    }// end of function

    //来自uct php微信开发框架，其中的checkString函数如下
    public static function checkString($var, $check = '', $default = '') {
        if (!is_string($var)) {
            if(is_numeric($var)) {
                $var = (string)$var;
            }
            else {
                return $default;
            }
        }
        if ($check) {
            return (preg_match($check, $var, $ret) ? $ret[1] : $default);
        }

        return $var;
    }
}// end of class
