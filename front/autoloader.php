<?php
    spl_autoload_register('loader');

function loader($class):void
{
    include_once "../".$class.".php";
}