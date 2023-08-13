<?php
    spl_autoload_register('loader');

function loader($class)
{
        include_once "../".$class.".php";
}