 #!/bin/bash

PIDS=$(ps aux | grep /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/crawl/cronjob.php | grep -v grep)

if [ -z "$PIDS" ]; then
	echo 0
else
 	echo 1
fi
