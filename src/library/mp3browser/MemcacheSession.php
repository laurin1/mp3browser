<?php

namespace mp3browser;

class MemcacheSession implements \SessionHandlerInterface{

	/** @var \Memcache */
	private $oMemache;

	/** @var int */
	private $iTTL;

	/** @var string */
	private $sPrefix;

	const _MEMCACHE_SERVER = "localhost";

	const _MEMCACHE_PORT = 11211;

	/**
	 * @param array $aOptions
	 */
	public function __construct(array $aOptions = []){

		$this->oMemache = new \Memcache();

		$this->oMemache->addServer(
			self::_MEMCACHE_SERVER,
			self::_MEMCACHE_PORT);

		/** @noinspection PhpAssignmentInConditionInspection */
		if(
		$aInvalidOptions =
			array_diff(
				array_keys($aOptions),
				[
					"prefix",
					"expiretime"
				])
		)
			throw new \InvalidArgumentException(
				"The following options are not supported \"".implode(", ", $aInvalidOptions)."\"");

		$this->iTTL    =
			isset($aOptions["expiretime"]) ?
				(int) $aOptions["expiretime"] :
				ini_get("session.cache_expire") * 60;
		$this->sPrefix =
			isset($aOptions["prefix"]) ?
				$aOptions["prefix"] :
				"sf2s-";

	}

	/**
	 * @param string $sSavePath
	 * @param string $sSessionName
	 * @return bool
	 */
	public function open($sSavePath, $sSessionName){

		return true;

	}

	/**
	 * @return bool
	 */
	public function close(){

		return true;

	}

	/**
	 * @param string $sSessionID
	 * @return string
	 */
	public function read($sSessionID){

		return $this->oMemache->get($this->getSessionIDKey($sSessionID)) ?: "";

	}

	/**
	 * @param string $sSessionID
	 * @param string $sData
	 * @return bool
	 */
	public function write($sSessionID, $sData){

		return $this->oMemache->set($this->getSessionIDKey($sSessionID), $sData, 0, time() + $this->iTTL);

	}

	/**
	 * @param string $sSessionID
	 * @return bool
	 */
	public function destroy($sSessionID){

		return $this->oMemache->delete($this->getSessionIDKey($sSessionID));

	}

	/**
	 * @param int $iMaxLifetime
	 * @return bool
	 */
	public function gc($iMaxLifetime){

		return true;

	}

	/**
	 * @param string $sSessionID
	 * @return string
	 */
	private function getSessionIDKey($sSessionID): string{

		return $this->sPrefix.$sSessionID;

	}

}
