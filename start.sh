#!/bin/bash
#MyPcIp="192.168.255.161"
#echo ${MyPcIp}

param1=$1

case ${param1} in
     "start")
        echo -e "start \n"
        php Novel/NovelAdmin/index.php start -d
        ;;
     "stop")
        echo -e "server will stop \n";
        php Novel/NovelAdmin/index.php stop
        ;;
     c)
        echo "c"
        ;;
     ?)  #当有不认识的选项的时候arg为?
        echo "unkonw argument"
        exit 1
        ;;
esac
