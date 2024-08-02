<?php
function CP_Insert($sql,$data)
{
	try {  
		global $db;
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$result = $db->prepare($sql);
		$result->execute($data);
		$id = $db->lastInsertId();
		return $id;
	}
	catch(Exception $e) {		
		error_log($e->getMessage()); 
	}
}
function CP_Delete($sql,$data){
	try {	        
		global $db;				
		$result = $db->prepare($sql);
		$result->execute($data);
		return $result->rowCount();
    }
	catch(Exception $e){		
		error_log($e->getMessage()); 
    }
}
function CP_Select($sql,$data){
	try {	        
		global $db;
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$result = $db->prepare($sql);
		$result->execute($data);	
		$result = $result->fetchAll(PDO::FETCH_OBJ); 		
		return $result;
	}		
	catch(Exception $e){		
		error_log($e->getMessage()); 
    }
}
function CP_Update($sql,$data){
	try {	        
		global $db;
		$result = $db->prepare($sql);
		$result->execute($data);
		return $result->rowCount();
	}
	catch(Exception $e){		
		error_log($e->getMessage()); 
    }
}
function CP_Count($sql,$data){
	try {	        
		global $db;
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$result = $db->prepare($sql);
		$result->execute($data);
		return $result->fetchColumn();		
	}
	catch(Exception $e){		
		error_log($e->getMessage()); 
    }
}
