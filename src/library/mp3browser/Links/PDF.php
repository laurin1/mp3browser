<?php

namespace mp3browser\Links;

use mp3browser\Abstracts\View;

class PDF implements \mp3browser\Interfaces\Link{
	/** @var string */
	public $sLink;

	/**
	 * MP3 constructor.
	 * @param string $sBaseFolder
	 * @param string $sFileName
	 * @param View   $oView
	 */
	function __construct(string $sBaseFolder, string $sFileName, View $oView){

		$this->sLink =
			'<a href="/download.php?sFolderName='.
			(
			$oView->getCurrentFolder() ?
				str_replace(
					$sBaseFolder,
					"",
					$oView->getCurrentFolder())."/" :
				"/"
			).
			'&sFileName='.
			urlencode($sFileName).
			'&sFileType=application/pdf">'.
			$sFileName.
			'</a>';

	}

	/**
	 * @return string
	 */
	public function getLink(): string{

		return $this->sLink;

	}

}