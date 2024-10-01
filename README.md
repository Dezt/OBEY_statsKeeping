This code is for hosting your own trophy server mod for the video game OBEY

https://obeygame.com

https://store.steampowered.com/app/346970/OBEY/


The trophy server is meant to be moddable, so doesn't require any database or special setup.  
Simply change the password in passwords.php, upload these files to a webserver, and set the same password in your OBEY server's settings.

By editing leaderboardPostTest.php you can change what each symbol means or what they look like.  Your OBEY server will be pinging the php with the results of each game, and you can change it to do what you want with that info: add your own awards/trophies, change what they look like, or what they represent, etc.
read more here: https://wiki.obeygame.com/index.php?title=LeaderboardPost.php
