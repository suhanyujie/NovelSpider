<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/6/18
 * Time: 15:29
 */
$url = "http://127.0.0.1:3001/?v=1";
httpResponse($url,200);

function httpResponse($url, $status = null, $wait = 3)
{
    $time = microtime(true);
    $expire = $time + $wait;

    // we fork the process so we don't have to wait for a timeout
    $pid = pcntl_fork();
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // we are the parent
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        $headerArr = [
            'X-HTTP-Method-Override: PUT',
            'Test-Header-1: PUT',
            'Test-Header-2: PUT',
            'Transfer-Encoding: chunked',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        // curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if(!$head)
        {
            return FALSE;
        }

        if($status === null)
        {
            if($httpCode < 400)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        elseif($status == $httpCode)
        {
            return TRUE;
        }

        return FALSE;
        pcntl_wait($status); //Protect against Zombie children
    } else {
        // we are the child
        while(microtime(true) < $expire)
        {
            sleep(0.5);
        }
        return FALSE;
    }
}

