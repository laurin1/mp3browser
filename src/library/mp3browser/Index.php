<?php
namespace mp3browser;

use fkooman\Config\IniFile;
use fkooman\Config\Reader;

class Index{

	/**
	 * @return string
	 */
	public function getHTML(): string{

		return
			"<!DOCTYPE html><head></head><html><body>".
			$this->getAudioControls().
			'<h2 id="Top">TABLE OF CONTENTS</h2>'.
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
				'</div>'.
				'<hr />';

		}

		return "";

	}

	/**
	 * @return string
	 */
	private function getFilesAndFolders(): string{

		$sBaseFolder           = $this->getBaseFolderFromINI();
		$sFolder               =
			isset($_GET["folder"]) && $_GET["folder"] ?
				$_GET["folder"] :
				$sBaseFolder;
		$oDirectory            = new \DirectoryIterator($sFolder);
		$sFolders              = "";
		$sFiles                = "";
		$sCurrentLetterSection = "";
		$sLetterLinks          = "";

		foreach($oDirectory as $oFile){

			if(
				(
					!$oFile->isDir() &&
					$oFile->getExtension() != "mp3"
				) ||
				$oFile->getFilename() === ".sync"
			)
				continue;

			if($oFile->isDot()){

				if(
					$sFolder !== $sBaseFolder &&
					$oFile->getFilename() === ".."
				)
					$sFolders .=
						'<a href="'.$_SERVER["PHP_SELF"].'?folder='.dirname($sFolder).'">'.
						'<-- RETURN</a> (DIR)<br />';

				continue;

			}

			if($oFile->isDir()){

				$sFolders .=
					'<a href="'.$_SERVER["PHP_SELF"].'?folder='.$sFolder."/".$oFile->getFilename().'">'.
					$oFile->getFilename().
					'</a> (FOLDER)<br />';

				continue;

			}

			$sCurrentLetter = strtoupper(substr($oFile->getFilename(), 0, 1));

			if($sCurrentLetterSection !== $sCurrentLetter){

				$sFiles .= $this->getLetterSectionTitle($sCurrentLetter);
				$sLetterLinks .= $this->getLetterLink($sCurrentLetter)." ";

				$sCurrentLetterSection = $sCurrentLetter;

			}

			$sFiles .=
				'<a href="'.$_SERVER["PHP_SELF"].'?src='."/mp3files/".$oFile->getFilename().'">'.
				$oFile->getFilename().
				"</a>".
				"<br />";

		}

		return
			$sFolders.
			'<hr />'.
			'<h3>'.$sLetterLinks.'</h3>'.
			$sFiles;

	}

	/**
	 * @param string $sLetter
	 * @return string
	 */
	private function getLetterSectionTitle(string $sLetter): string{

		return
			'<h3 id="'.$sLetter.'">'.$sLetter." ".$this->getBookmarkLink("Top").'</h3>';

	}

	/**
	 * @param string $sLetter
	 * @return string
	 */
	private function getLetterLink(string $sLetter): string{

		return $this->getBookmarkLink($sLetter);

	}

	/**
	 * @param string $sElementID
	 * @return string
	 */
	private function getBookmarkLink(string $sElementID): string{

		return '<a href="#'.$sElementID.'">'.$sElementID.'</a>';

	}

	/**
	 * @return string
	 */
	private function getBaseFolderFromINI(): string{

		$oReader =
			new Reader(
				new IniFile(dirname(dirname(dirname(dirname(__FILE__))))."/config.ini"));

		return $oReader->v("Settings", "base_folder");

	}

}