<?php

define ('DSN', 'ztiny.sqlite');
define ('MINLENGTH', 4);

function initDB() 
{
	try {
	    $dbh = new PDO("sqlite:" . DSN);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {  
	    error_log( $e->getMessage() );
	    exit(1);
	}
	return $dbh;
}

function insertURL( $url = null ) 
{
	if ($url == null) {
		return array('status' => -1, 'msg' => 'Missing URL');
	}
	$dbh = initDB();
	$hash = sha1($url);
	$length = MINLENGTH;
	$index = lookup(substr($hash, 0, $length));
	while ($index != null) {
		if ($index['url'] == $url) {
#			print "$url already exists\n";
			$hash = null;
			$dbh = null;
			$index['status'] = 0; 
			$index['msg'] = 'ok';
			return $index;
		}
		++$length;
		print_r($index);
		$index = lookup(substr($hash, 0, $length));
	}
	
	$sql = 'insert into url values (:hash, :url)';
	$stmt = $dbh->prepare($sql);
	$index = substr($hash, 0, $length);
	$stmt->bindParam(':hash', $index, PDO::PARAM_STR);
	$stmt->bindParam(':url', $url, PDO::PARAM_STR);
	$stmt->execute();
	$hash = null;
	$dbh = null;
	return array('uid' => $index, 'url' => $url, 'status' => 0, 'msg' => 'ok');
}

## NOTE: only lookup(hash) is defined, lookup by URL is not defined for obvious reasons :)

function lookup($hash = null) 
{
	if ($hash == null) return null;
	$dbh = initDB();
#	print "looking up $hash\n";
	$sql = 'select * from url where uid = :hash';
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt = null;
	$dbh = null;
	return $result;	
}

