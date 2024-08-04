<?php 
if(
	(
	
		(
			isset($_GET["guid"]) && $_GET["guid"] !=""
			&& isset($_GET["uid"]) && $_GET["uid"] !=""
		)

	)
)
{
	if(url_crypt($_GET["guid"] , 'd') == $_GET["uid"])
	{
		
		$_SESSION["userid"] = url_crypt($_GET["uid"] , 'd');
		{
			$db_main = DB_MAIN;
			$db_base = DB_BASE;		
		global $db_Base,$db_Extend,$db_Main;
		$db_Main = $db_main;
		$db_Base = $db_base;
		}
	}
	
	else
	{
		header('location:'.SITE_URL.'/invalid-request/');
		exit;
	}
}
?>