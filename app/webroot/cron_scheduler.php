<?php

#
# BEGIN
#
# This was added to webroot/cron_scheduler.php
#

# Check that URI was specified and that we're called from the command line (not the web)
if($argc == 2 && php_sapi_name() === "cli")
{
    # Set request URI
    $_SERVER['REQUEST_URI'] = $argv[1];
    # Set user-agent, so we can do custom processing
    $_SERVER['HTTP_USER_AGENT'] = 'cron';

    $Dispatcher= new Dispatcher();
    $Dispatcher->dispatch($argv[1]);
}

#
# END
#
#
#

?>
