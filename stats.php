<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('eventsDB.sqlite');
      }
   }
   
   $db = new MyDB();
   if(!$db)
      echo $db->lastErrorMsg();
   else 
      echo "Opened database successfully\n";

   $sql = 'SELECT * FROM sqlite_master WHERE name =\'events\' and type=\'table\'; ';
   $stmt = $db->prepare( $sql);
   $ret = $stmt->execute();
   
   if ($ret->fetchArray() == false) //create the table if needed.
   {
	   $sql = 
		'CREATE TABLE `events` 
		(
		`time`	INTEGER,
		`event`	INTEGER NOT NULL,
		`a0`	TEXT NOT NULL,
		`a1`	TEXT,
		`a2`	TEXT,
		`a3`	TEXT,
		`a4`	TEXT,
		`a5`	TEXT,
		`a6`	TEXT,
		`a7`	TEXT,
		`a8`	TEXT,
		`a9`	TEXT,
		`a10`	TEXT,
		`a11`	TEXT,
		PRIMARY KEY(`event`)
		)';
		$db->query($sql);
   }
   
   //add the stats received from the OBEY server.

   $db->close();
?>