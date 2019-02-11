#!/usr/bin/env bash

echo "Startup"
/var/startup/wait-for-it/wait-for-it.sh -h mysql -p 3306 -- bash /var/startup/install.bash
