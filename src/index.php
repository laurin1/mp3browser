<?php
echo "<!DOCTYPE html><html><body>";

$oDirectory = new DirectoryIterator("D:/AA Talks - MP3");

foreach($oDirectory as $oFile){

	if(
		$oFile->isDir() ||
		$oFile->isDot()
	)
		continue;

	$sSrc = "/mp3files/".$oFile->getFilename();

	echo
		'<br /><br /><br />
		<audio controls>
			<source src="'.$sSrc.'" type="audio/mpeg">
			</audio>
			<br />';

	break;

}

echo "</body></html>";