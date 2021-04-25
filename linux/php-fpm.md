#配置
```
listen = /run/php/php7.4-fpm.sock 和 nginx  fastcgi_pass   unix:/run/php/php7.4-fpm.sock

# 一般进程数和服务器cpu相当,太多会影响系统上下文切换时间
pm.start_servers = 4 //默认进程
pm.max_children = 8 //动态最大值

```
#配置php-fpm service启动,移动到 /etc/init.d/
```
#!/bin/sh
# Comments to support chkconfig on CentOS
# chkconfig: 2345 65 37
#
set -e

PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/opt/remi/php74/root/bin:/opt/remi/php74/root/sbin
DESC="php-fpm daemon"
NAME=php-fpm
DAEMON=/opt/remi/php74/root/sbin/$NAME

CONFIGFILE=/etc/opt/remi/php74/php-fpm.conf
PIDFILE=/var/opt/remi/php74/run/php-fpm/$NAME.pid
SCRIPTNAME=/etc/init.d/$NAME

# Gracefully exit if the package has been removed.
test -x $DAEMON || exit 0

d_start() {
  $DAEMON -y $CONFIGFILE || echo -n " already running"
}

d_stop() {
  kill -QUIT `cat $PIDFILE` || echo -n " not running"
}

d_reload() {
  kill -HUP `cat $PIDFILE` || echo -n " can't reload"
}

case "$1" in
  start)
        echo -n "Starting $DESC is success"
        d_start
        echo "."
        ;;
  stop)
        echo -n "Stopping $DESC is success"
        d_stop
        echo "."
        ;;
  reload)
        echo -n "Reloading $DESC configuration..."
        d_reload
        echo "reloaded."
  ;;
  restart)
        echo -n "Restarting $DESC is success"
        d_stop
        sleep 1
        d_start
        echo "."
        ;;
  *)
         echo "Usage: $SCRIPTNAME {start|stop|restart|force-reload}" >&2
         exit 3
        ;;
esac

```

```
#! /bin/sh

### BEGIN INIT INFO
# Provides:          php-fpm
# Required-Start:    $remote_fs $network
# Required-Stop:     $remote_fs $network
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: starts php-fpm
# Description:       starts the PHP FastCGI Process Manager daemon
### END INIT INFO

prefix=/opt/php
exec_prefix=${prefix}

php_fpm_BIN=${exec_prefix}/sbin/php-fpm
php_fpm_CONF=${prefix}/etc/php-fpm.conf
php_fpm_PID=${prefix}/var/run/php-fpm.pid


php_opts="--fpm-config $php_fpm_CONF --pid $php_fpm_PID"


wait_for_pid () {
        try=0

        while test $try -lt 35 ; do

                case "$1" in
                        'created')
                        if [ -f "$2" ] ; then
                                try=''
                                break
                        fi
                        ;;

                        'removed')
                        if [ ! -f "$2" ] ; then
                                try=''
                                break
                        fi
                        ;;
                esac

                echo -n .
                try=`expr $try + 1`
                sleep 1

        done

}

case "$1" in
        start)
                echo -n "Starting php-fpm "

                $php_fpm_BIN --daemonize $php_opts

                if [ "$?" != 0 ] ; then
                        echo " failed"
                        exit 1
                fi

                wait_for_pid created $php_fpm_PID

                if [ -n "$try" ] ; then
                        echo " failed"
                        exit 1
                else
                        echo " done"
                fi
        ;;

        stop)
                echo -n "Gracefully shutting down php-fpm "

                if [ ! -r $php_fpm_PID ] ; then
                        echo "warning, no pid file found - php-fpm is not running ?"
                        exit 1
                fi

                kill -QUIT `cat $php_fpm_PID`

                wait_for_pid removed $php_fpm_PID

                if [ -n "$try" ] ; then
                        echo " failed. Use force-quit"
                        exit 1
                else
                        echo " done"
                fi
        ;;

        status)
                if [ ! -r $php_fpm_PID ] ; then
                        echo "php-fpm is stopped"
                        exit 0
                fi

                PID=`cat $php_fpm_PID`
                if ps -p $PID | grep -q $PID; then
                        echo "php-fpm (pid $PID) is running..."
                else
                        echo "php-fpm dead but pid file exists"
                fi
        ;;

        force-quit)
                echo -n "Terminating php-fpm "

                if [ ! -r $php_fpm_PID ] ; then
                        echo "warning, no pid file found - php-fpm is not running ?"
                        exit 1
                fi

                kill -TERM `cat $php_fpm_PID`

                wait_for_pid removed $php_fpm_PID

                if [ -n "$try" ] ; then
                        echo " failed"
                        exit 1
                else
                        echo " done"
                fi
        ;;

        restart)
                $0 stop
                $0 start
        ;;

        reload)

                echo -n "Reload service php-fpm "

                if [ ! -r $php_fpm_PID ] ; then
                        echo "warning, no pid file found - php-fpm is not running ?"
                        exit 1
                fi

                kill -USR2 `cat $php_fpm_PID`

                echo " done"
        ;;

        *)
                echo "Usage: $0 {start|stop|force-quit|restart|reload|status}"
                exit 1
        ;;

esac

```