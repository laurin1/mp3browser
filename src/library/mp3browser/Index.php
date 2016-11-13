<?php
namespace mp3browser;

class Index{

	/**
	 * @return string
	 */
	public function getHTML(): string{

		$sReturn = "<!DOCTYPE html><head></head><html><body>";

		if(isset($_GET["src"]) && $_GET["src"])
			$sReturn .=
				$this->getAudioControls($_GET["src"]);

		$oDirectory = new \DirectoryIterator("D:/AA Talks - MP3");

		foreach($oDirectory as $oFile){

			if(
				$oFile->isDir() ||
				$oFile->isDot() ||
				$oFile->getExtension() === "nra"
			)
				continue;

			$sSrc = "/mp3files/".$oFile->getFilename();

			$sReturn .=
				'<a href="'.$_SERVER["PHP_SELF"].'?src='.$sSrc.'">'.$oFile->getFilename().'</a><br />';

		}

		$sReturn .= "</body></html>";

		return $sReturn;

	}

	/**
	 * @param string $sSrc
	 * @return string
	 */
	private function getAudioControls(string $sSrc): string{

		return
			'
			<div style="">
				<audio controls style="height: 100px;">
					<source src="'.$sSrc.'" type="audio/mpeg">
				</audio>
			</div>
			<div style="margin-bottom:10px;font-size:2em;">'.
			pathinfo($sSrc, PATHINFO_FILENAME).
			'</div>';


	}

}