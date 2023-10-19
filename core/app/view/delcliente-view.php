<?php


$cliente = ClienteData::getById1($_GET["id"]);
$cliente->del1();

Core::redir("./index.php?view=cliente");
