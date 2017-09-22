<?php

require_once('../_init.php');

$app = vsource();
var_dump($app->dbal()->fetchAll('SELECT * FROM tbl_oauth2'));

echo '<hr>';

$qb = $app->dbal()->createQueryBuilder()->select('*')->from('tbl_oauth2')->where('sessionid = :sessionid')->setParameter('sessionid', session_id());

//var_dump($qb->getQuery());
var_dump($qb->execute()->fetchAll());