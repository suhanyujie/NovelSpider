<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/16
 * Time: 下午6:31
 */

/**
 * @desc ICMP协议的实现
 */
function sendPackage($host,$type,$code,$data,$callbackFunc)
{
    $g_icmp_error = null;
    //封装icmp报文
    $package = chr($type).chr($code);
    $package .= chr(0).chr(0);
    $package .= $data;
    //设置校验和
    setSum($package);

}

//计算校验和
function setSum(&$data)
{
    $list   = unpack('n*', $data);
    $length = strlen($data);
    $sum    = array_sum($list);
    if ($length % 2) {
        $tmp = unpack('C*', $data[$length - 1]);
        $sum += $tmp[1];
    }
    $sum     = ($sum >> 16) + ($sum & 0xffff);
    $sum     += $sum >> 16;
    $r       = pack('n*', ~$sum);
    $data[2] = $r[0];
    $data[3] = $r[1];
}

