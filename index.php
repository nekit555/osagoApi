<?php
require_once( 'OsagoApi.php' );

$osagoApi = new OsagoApi('123','http://osago.ads-soft.ru');
echo $osagoApi->getStatusList();