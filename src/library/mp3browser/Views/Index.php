<?php

namespace mp3browser\Views;

use fkooman\Config\IniFile;
use fkooman\Config\Reader;

class Index extends \mp3browser\Abstracts\View implements \mp3browser\Interfaces\View{

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
			$this->getFilesAndFolders(
				$this->getBaseFolderFromINI(),
				[
					"mp3"
				]
			).
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
	 * @return string
	 */
	private function getBaseFolderFromINI(): string{

		$oReader =
			new Reader(
				new IniFile(dirname($_SERVER["DOCUMENT_ROOT"])."/config.ini"));

		return $oReader->v("Settings", "base_folder");

	}

}