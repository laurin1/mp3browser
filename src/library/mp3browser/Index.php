<?php
namespace mp3browser;

class Index{

	/**
	 * @return string
	 */
	public function getHTML(): string{

		return
			"<!DOCTYPE html><head></head><html><body>".
			$this->getAudioControls().
			$this->getFilesAndFolders().
			"</body></html>";

	}

	/**
	 * @return string
	 */
	private function getAudioControls(): string{

		if(isset($_GET["src"]) && $_GET["src"]){

			$sSrc = $_GET["src"];

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

		return "";

	}

	/**
	 * @return string
	 */
	private function getFilesAndFolders(): string{

		$sBaseFolder = "D:/AA Talks - MP3";
		$sFolder     =
			isset($_GET["folder"]) && $_GET["folder"] ?
				$_GET["folder"] :
				$sBaseFolder;
		$oDirectory  = new \DirectoryIterator($sFolder);
		$sFolders    = "";
		$sFiles      = "";

		foreach($oDirectory as $oFile){

			if(
				$oFile->getExtension() === "nra" ||
				$oFile->getFilename() === ".sync"
			)
				continue;

			if($oFile->isDot()){

				if(isset($_GET["folder"]))
					$sFolders .=
						'<a href="'.$_SERVER["PHP_SELF"].'?folder='.dirname($sFolder).'">'.
						$oFile->getFilename().
						'</a> (DIR)<br />';

				continue;

			}

			if($oFile->isDir()){

				$sFolders .=
					'<a href="'.$_SERVER["PHP_SELF"].'?folder='.$sFolder."/".$oFile->getFilename().'">'.
					$oFile->getFilename().
					'</a> (FOLDER)<br />';

				continue;

			}

			$sFiles .=
				'<a href="'.$_SERVER["PHP_SELF"].'?src='."/mp3files/".$oFile->getFilename().'">'.
				$oFile->getFilename().
				"</a>".
				"<br />";

		}

		return $sFolders.$sFiles;

	}

}