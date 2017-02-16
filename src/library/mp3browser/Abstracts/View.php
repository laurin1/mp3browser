<?php

namespace mp3browser\Abstracts;

abstract class View{

	/** @var string */
	private $sCurrentFolder = "";

	/**
	 * @param string $sBaseFolder
	 * @param array  $aExtensions
	 * @return string
	 */
	protected function getFilesAndFolders(string $sBaseFolder, array $aExtensions): string{

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

			$sExtension = $oFile->getExtension();

			if(
				(
					!$oFile->isDir() &&
					!in_array(
						$sExtension,
						$aExtensions)
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

			$sFileName = $oFile->getFilename();

			if($oFile->isDir()){

				$sFolders .=
					'<a href="'.$_SERVER["PHP_SELF"].'?folder='.$sFolder."/".
					urlencode($oFile->getFilename()).'">'.
					$sFileName.
					'</a> (FOLDER)'.$this->getBR();

				continue;

			}

			$sCurrentLetter = strtoupper(substr($sFileName, 0, 1));

			if($sCurrentLetterSection !== $sCurrentLetter){

				$sFiles       .= $this->getLetterSectionTitle($sCurrentLetter);
				$sLetterLinks .= $this->getLetterLink($sCurrentLetter)." ";

				$sCurrentLetterSection = $sCurrentLetter;

			}

			$sClass = "mp3browser\\Links\\".strtoupper($sExtension);
			/** @var \mp3browser\Links\MP3 $oLink */
			$oLink  =
				new $sClass(
					$sBaseFolder,
					$sFileName,
					$this
				);
			$sFiles .=
				$oLink->getLink().
				$this->getBR();

		}

		return
			$sFolders.
			'<hr />'.
			'<h3>'.$sLetterLinks.'</h3>'.
			$sFiles;

	}

	/**
	 * @param string $sElementID
	 * @return string
	 */
	private function getBookmarkLink(string $sElementID): string{

		return '<a href="#'.$sElementID.'">'.$sElementID.'</a>';

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
	 * @return string
	 */
	protected function getBR(): string{

		return "<br />";

	}

	/**
	 * @return string
	 */
	public function getCurrentFolderParameter(): string{

		if(!$this->sCurrentFolder)
			return "";

		return "folder=".$this->sCurrentFolder."&";

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
	 * @return string
	 */
	public function getCurrentFolder(){

		return $this->sCurrentFolder;

	}

}
