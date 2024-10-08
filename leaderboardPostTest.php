<?php 	
	
	/* 
	//password and credentials should be kept separate from the code itself.
	//Hence, passwords.php should contain only:
	<?php 
		//this password is specified as leaderboardPostPassword=myPassword in serverSettings.txt so that only authorized servers can post to this php. 
		//Otherwise fake/unearned trophies can be easily hacked.
		$postPW = "myPassword"; 
		$awardsFile = 'awards.txt';	
	?>
	*/
	include("passwords.php");	
	
	

	if ($_GET["pw"] != $postPW)
	{
		exit;
	}

	//convert what the server told us happened, into some kind of "record or trophy" that can be displayed next to the player's name
	//the format is: playerName^playerID^b1:00ff00#b2:00ff00#a1:00ff00
	//OBEY servers will read this and search it for players that are joining.  
	//Each time they find the player's ID (the name is just for convenience of manual editing) it will display the award in the score board
	//a1 = a vertical line next to the name
	//a2 = a thick vertical line 
	//a3 = a large cube
	//b1 = a small dot 
	//b2 = 2 small dots , one over the other
	
	 
	$trophy = $_GET["trophy"];	
	$profileID = $_GET["profileID"];	 
	$playerName = $_GET["playerName"];	
	
	if ($profileID == "" || $playerName == "" || $trophy == "")
		exit;
	
	 
	 if ($trophy != "1st" && $trophy != "2nd" && $trophy != "3rd" && $trophy != "1stplus" && $trophy != "rare")
	 {
		 echo "[ff0000]Trophy ".$trophy." not recognized.[-]"; //this will be printed to everyone on the server
		 exit;
	 }

	
	$data = file($awardsFile); // reads an array of lines
	$recordExists = 0;

	
	$outData = array();
	//below, we transcribe each line from $data into $outData, making any needed changes as we go.
	for ($i = 0; $i < Count($data); $i++)
	{
		if (strncmp($data[$i], $profileID, strlen($profileID)) === 0)  //does the line starts with the profileID?
		{
			$recordExists = 1;
			 			 
			//abcd2234^stuphChicken^b1:00ff00#b1:00ff00#b1:00ff00#
			//we may have a mix of dots.. so lets order them. First place on the left, 2nd markers in the middle, 3rds on the right. 
			//count any we may have
			$unique = (int)substr_count($data[$i], 'a1:0000ff'); 
			$firstpluses = (int)substr_count($data[$i], 'b1:ff00ff'); 
			$firstpluses += (int)substr_count($data[$i], 'b2:ff00ff') * 2; 
			$firstpluses += (int)substr_count($data[$i], 'a1:ff00ff') * 10; 
			$firstpluses += (int)substr_count($data[$i], 'a3:ff00ff') * 50; 
			$firsts = (int)substr_count($data[$i], 'b1:00ff00'); 
			$firsts += (int)substr_count($data[$i], 'b2:00ff00') * 2;
			$firsts += (int)substr_count($data[$i], 'a1:00ff00') * 10;
			$firsts += (int)substr_count($data[$i], 'a3:00ff00') * 50;
			$seconds = (int)substr_count($data[$i], 'b1:00ffff'); 
			$seconds += (int)substr_count($data[$i], 'b2:00ffff') * 2;
			$seconds += (int)substr_count($data[$i], 'a1:00ffff') * 10;
			$seconds += (int)substr_count($data[$i], 'a2:00ffff') * 50;
			$thirds = (int)substr_count($data[$i], 'b1:008989'); 
			$thirds += (int)substr_count($data[$i], 'b2:008989') * 2;
			$thirds += (int)substr_count($data[$i], 'a1:008989') * 10;
			$thirds += (int)substr_count($data[$i], 'a2:008989') * 50;
			 
			//add the trophy given by the url
			if ($trophy == "1st") //won first place
				 $firsts++;
			else if ($trophy == "2nd") 
				 $seconds++;
			else if ($trophy == "3rd") 
				 $thirds++;
			else if ($trophy == "1stplus") 
				 $firstpluses++;
			else if ($trophy == "rare") 
				 $unique++;
			else
			{
				 echo "[ff0000]Trophy ".$trophy." not recognized.[-]"; //this will be printed to everyone on the server
				 exit;
			}

			//reorganize and print all awards, so that they appear in order 1st, 2nd, 3rd place wins
			$awards = "";
			
			for ($d = 0; $d < $unique; $d++) //these don't stack. 
				$awards .= "a1:0000ff#";
			
			$n = (int)floor($firstpluses/ 50); //firstpluses/50 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "a3:ff00ff#";
			$firstpluses -= $n * 50; //these are accounted for, now.
			$n = (int)floor($firstpluses/ 10);
			for ($d = 0; $d < $n; $d++) 
				$awards .= "a1:ff00ff#";
			$firstpluses -= $n * 10; //these are accounted for, now.
			$n = (int)floor($firstpluses/ 2);//firstpluses/2 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "b2:ff00ff#";
			if ($firstpluses % 2 == 1)
				$awards .= "b1:ff00ff#";
				
			$n = (int)floor($firsts/ 50); //firsts/50 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "a3:00ff00#";
			$firsts -= $n * 50; //these are accounted for, now.
			$n = (int)floor($firsts/ 10);
			for ($d = 0; $d < $n; $d++) 
				$awards .= "a1:00ff00#";
			$firsts -= $n * 10; //these are accounted for, now.
			$n = (int)floor($firsts/ 2);//firsts/2 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "b2:00ff00#";
			if ($firsts % 2 == 1)
				$awards .= "b1:00ff00#";
			
			$n = (int)floor($seconds/ 50); //seconds/50 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "a3:00ffff#";
			$seconds -= $n * 50; //these are accounted for, now.
			$n = (int)floor($seconds/ 10);
			for ($d = 0; $d < $n; $d++) 
				$awards .= "a1:00ffff#";
			$seconds -= $n * 10; //these are accounted for, now.
			$n = (int)floor($seconds/ 2);//seconds/2 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "b2:00ffff#";
			if ($seconds % 2 == 1)
				$awards .= "b1:00ffff#";
			
			$n = (int)floor($thirds/ 50); //thirds/50 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "a3:008989#";
			$thirds -= $n * 50; //these are accounted for, now.
			$n = (int)floor($thirds/ 10);
			for ($d = 0; $d < $n; $d++) 
				$awards .= "a1:008989#";
			$thirds -= $n * 10; //these are accounted for, now.
			$n = (int)floor($thirds/ 2);//thirds/2 in a way that will not round or cast to float
			for ($d = 0; $d < $n; $d++)  
				$awards .= "b2:008989#";
			if ($thirds % 2 == 1)
				$awards .= "b1:008989#";

			$outData[Count($outData)] = $profileID."^".$playerName."^".$awards."\n";
	   }
	   else if ($data[$i] != "" && $data[$i] != "\n" && $data[$i] != "\n\n")
	   {
		   $outData[Count($outData)] = $data[$i]; //nothing noted, just copy this line to the end of the output
	   }
	}
	
	if ($recordExists == 0) //this is the first trophy for this player
	{
		if ($trophy == "1st") //won first place
			 $outData[Count($outData)] = $profileID."^".$playerName."^b1:00ff00\n"; //add the new record
		else if ($trophy == "2nd") 
			 $outData[Count($outData)] = $profileID."^".$playerName."^b1:00ffff\n"; 
		else if ($trophy == "3rd") 
			 $outData[Count($outData)] = $profileID."^".$playerName."^b1:008989\n"; 	
		else if ($trophy == "1stplus") 
			 $outData[Count($outData)] = $profileID."^".$playerName."^b1:ff00ff\n"; 	
		else if ($trophy == "rare") 
			 $outData[Count($outData)] = $profileID."^".$playerName."^a1:0000ff\n"; 
	}
	
	file_put_contents($awardsFile, implode("", $outData));
	
	//this will be printed to everyone on the server
	if ($trophy == "1st") //won first place
		echo "[00ff00]Congratulations! ". $playerName ." was awarded 1st place![-]"; 
	else if ($trophy == "2nd") 
		echo "[00ff00]Congratulations! ". $playerName ." was awarded 2nd place![-]"; 
	else if ($trophy == "3rd") 
		echo "[00ff00]Congratulations! ". $playerName ." was awarded 3rd place![-]"; 
	else if ($trophy == "1stplus") 
		echo "[00ff00]Congratulations! ". $playerName ." was awarded a special 1st place trophy![-]"; 
	else if ($trophy == "rare") 
		echo "[00ff00]Congratulations! ". $playerName ." was awarded a HIGHLY RARE trophy![-]"; 
	
?>