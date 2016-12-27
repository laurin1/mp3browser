<?php
namespace mp3browser;

use fkooman\Config\IniFile;
use fkooman\Config\Reader;

class Index{

	/** @var string */
	private $sCurrentFolder = "";

	/** @var string */
	private $sCurrentSrc = "";

	/**
	 * @return string
	 */
	public function getHTML(): string{

		return
			"<!DOCTYPE html><head></head><html><body>".
			$this->getAudioControls().
			'<h2 id="Top">'.
			'<a href="/">TABLE OF CONTENTS</a>'.
			'</h2>'.
			$this->getFilesAndFolders().
			"</body></html>";

	}

	/**
	 * @return string
	 */
	private function getAudioControls(): string{

		if(!$this->setCurrentSrc())
			return "";

		return
			'
			<div style="">
				<audio controls style="height: 100px;">
					<source src="'.$this->sCurrentSrc.'" type="audio/mpeg">
				</audio>
			</div>
			<div style="margin-bottom:10px;">'.
			'<span style="font-size:2em;">'.pathinfo($this->sCurrentSrc, PATHINFO_FILENAME).'</span>'.
			' - <a href="'.$this->sCurrentSrc.'">Download</a>'.
			'</div>'.
			'<hr />';


	}

	/**
	 * @return string
	 */
	private function getFilesAndFolders(): string{

		$sBaseFolder           = $this->getBaseFolderFromINI();
		$sFolder               =
			$this->setCurrentFolder() ?
				$this->sCurrentFolder :
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
						'<-- RETURN</a> (DIR)'.$this->getBR();

				continue;

			}

			if($oFile->isDir()){

				$sFolders .=
					'<a href="'.$_SERVER["PHP_SELF"].'?folder='.$sFolder."/".$oFile->getFilename().'">'.
					$oFile->getFilename().
					'</a> (FOLDER)'.$this->getBR();

				continue;

			}

			$sCurrentLetter = strtoupper(substr($oFile->getFilename(), 0, 1));

			if($sCurrentLetterSection !== $sCurrentLetter){

				$sFiles .= $this->getLetterSectionTitle($sCurrentLetter);
				$sLetterLinks .= $this->getLetterLink($sCurrentLetter)." ";

				$sCurrentLetterSection = $sCurrentLetter;

			}

			$sFiles .=
				$this->getMP3Link(
					$sBaseFolder,
					$oFile->getFilename()).
				$this->getBR();

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

	/**
	 * @return string
	 */
	private function getCurrentFolderParameter(): string{

		if(!$this->sCurrentFolder)
			return "";

		return "folder=".$this->sCurrentFolder."&";

	}

	/**
	 * @return bool
	 */
	private function setCurrentFolder(): bool{

		$this->sCurrentFolder =
			(
				isset($_GET["folder"]) &&
				$_GET["folder"]
			) ?
				$_GET["folder"] :
				"";

		return (bool) $this->sCurrentFolder;

	}

	/**
	 * @return bool
	 */
	private function setCurrentSrc(): bool{

		$this->sCurrentSrc =
			(
				isset($_GET["src"]) &&
				$_GET["src"]
			) ?
				$_GET["src"] :
				"";

		return (bool) $this->sCurrentSrc;

	}

	/**
	 * @param string $sBaseFolder
	 * @param string $sFileName
	 * @return string
	 */
	private function getMP3Link(string $sBaseFolder, string $sFileName): string{

		return
			'<a href="'.$_SERVER["PHP_SELF"].'?'.
			$this->getCurrentFolderParameter().
			'src=/mp3files'.
			(
			$this->sCurrentFolder ?
				str_replace(
					$sBaseFolder,
					"",
					$this->sCurrentFolder)."/" :
				"/"
			).
			$sFileName.'">'.
			$sFileName.
			"</a>";

	}

	/**
	 * @return string
	 */
	private function getBR(): string{

		return "<br />";
	}

}