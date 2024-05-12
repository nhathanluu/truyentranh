 #!/bin/bash

PIDS=$(ps aux | grep url="$1" | grep -v grep)

if [ -z "$PIDS" ]; then
	echo 0
else
 	echo 1
fi
