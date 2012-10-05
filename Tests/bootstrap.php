<?php

if (is_readable($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (is_readable($file = __DIR__.'/autoload.php.dist')) {
    require_once $file;
}
