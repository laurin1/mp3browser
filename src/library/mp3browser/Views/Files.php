<?php

namespace mp3browser\Views;

class Files extends \mp3browser\Abstracts\View implements \mp3browser\Interfaces\View{

	/**
	 * @return string
	 */
	public function getHTML(): string{

		return
			"<!DOCTYPE html><head></head><html><body>".
			(
			$this->isAuthorized() ?
				$this->getContent() :
				$this->getLoginForm()
			).
			"</body></html>";

	}

	/**
	 * @return string
	 */
	private function getContent(): string{

		return
			'<h2 id="Top">'.
			'<a href="/files.php">TABLE OF CONTENTS</a>'.
			'</h2>'.
			$this->getFilesAndFolders(
				dirname($_SERVER["DOCUMENT_ROOT"])."/files",
				[
					"pdf"
				]
			);

	}

	/**
	 * @return string
	 */
	private function getLoginForm(): string{

		return
			'<form action="files.php">'.
			'<table>'.
			'<tr>'.
			'<td>Password:</td>'.
			'<td><input type="password" name="password"></td>'.
			'</tr>'.
			'<tr>'.
			'<td>Login:</td>'.
			'<td><input type="submit" name="login"></td>'.
			'</tr>'.
			'</table>'.
			'</form>';

	}

	/**
	 * @return bool
	 */
	private function isAuthorized(){

		if(isset($_GET["password"])){

			if($_GET["password"] === "Freeis4me!")
				$_SESSION["bAuthorized"] = true;

			header("location: ".$_SERVER["PHP_SELF"]);

		}
		elseif(
			isset($_SESSION["bAuthorized"]) &&
			$_SESSION["bAuthorized"]
		)
			return true;

		return false;

	}

}