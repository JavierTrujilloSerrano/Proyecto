<?php

use Symfony\Component\ErrorHandler\ErrorHandler;

set_exception_handler([new ErrorHandler(), 'handleException']);
require dirname(__DIR__).'/config/bootstrap.php';