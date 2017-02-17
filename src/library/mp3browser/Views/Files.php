<?php

namespace mp3browser\Views;

class Files extends \mp3browser\Abstracts\View implements \mp3browser\Interfaces\View{

	/**
	 * @return string
	 */
	public function getHTML(){

		$oDirectory = new \DirectoryIterator("../files");
		$aLinks     = [];

		foreach($oDirectory as $oFile)
			$aLinks[] =
				'<a href="/download.php?sFileName='.
				urlencode($oFile->getFilename()).'&sFileType=application/pdf">'.
				$oFile->getFilename().
				'</a>';

		return implode("<br />", $aLinks);

	}

}