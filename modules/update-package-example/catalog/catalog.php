<?php

function catalog_execute($con)
{
	catalog_test_insert($con);
}


function catalog_test_insert($con)
{
	mysqli_query($con, "INSERT INTO `config` (`smtp_host`) VALUES ('catalog test');");
}
