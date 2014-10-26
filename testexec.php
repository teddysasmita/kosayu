<?php

date_default_timezone_set('Asia/Jakarta');
$filename = date('ymdHis').'-backup.bz2';
exec('mysqldump -uroot -ptest gsi | bzip2 > main-'.$filename);
exec('mysqldump -uroot -ptest gsi-auth | bzip2 > auth-'.$filename);
exec('mysqldump -uroot -ptest gsi-track | bzip2 > track-'.$filename);
exec('mysqldump -uroot -ptest gsi-view | bzip2 > view-'.$filename);
?>
