#!/bin/bash
CRON_JOB="*/5 * * * * php $(pwd)/cron.php"
(crontab -l 2>/dev/null | grep -v "cron.php"; echo "$CRON_JOB") | crontab -
echo "CRON job added to run every 5 minutes."
