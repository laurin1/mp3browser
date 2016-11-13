<?php
namespace mp3browser;

class Index{

	/**
	 * @return string
	 */
	public function getHTML(): string{

		$sReturn = "<!DOCTYPE html><head></head><html><body>";

		$oDirectory = new \DirectoryIterator("D:/AA Talks - MP3");

		foreach($oDirectory as $oFile){

			if(
				$oFile->isDir() ||
				$oFile->isDot()
			)
				continue;

			$sSrc = "/mp3files/".$oFile->getFilename();

			$sReturn .=
				'
<div style="margin-top: 20px;">
	<audio controls style="height: 100px;">
		<source src="'.$sSrc.'" type="audio/mpeg">
	</audio>
</div>
<br />';

			break;

		}

		$sReturn .= "</body></html>";

		return $sReturn;

	}
}