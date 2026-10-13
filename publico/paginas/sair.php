<?php
//encerra a sessão

$_SESSION =[];
session_destroy();

redirecionar('/');