<?php

namespace mp3browser\Links;

use mp3browser\Abstracts\View;

class MP3 implements \mp3browser\Interfaces\Link{

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
			'<a href="'.$_SERVER["PHP_SELF"].'?'.
			$oView->getCurrentFolderParameter().
			'src=/mp3files'.
			(
			$oView->getCurrentFolder() ?
				str_replace(
					$sBaseFolder,
					"",
					$oView->getCurrentFolder())."/" :
				"/"
			).
			urlencode($sFileName).'">'.
			$sFileName.
			"</a>";

	}

	/**
	 * @return string
	 */
	public function getLink(): string{

		return $this->sLink;

	}

}