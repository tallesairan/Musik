<?php

namespace YTDownloader;
use YTDownloader\Exceptions\YoutubeDL as YoutubeDL;

class Download {
	/**
	 * Stores the Youtube URL
	 * @var string
	 */
	private $_url;

	/**
	 * Stores the Youtube Video ID
	 * @var string
	 */
	private $_videoId;

	/**
	 * Downloaded Video File Name
	 * @var string
	 */
	private $_file;

	/**
	 * Converted Video file name
	 * @var string
	 */
	private $_converted;

	/**
	 * Stores the default download location
	 * @var string
	 */
	private $_root;

	public function __construct($url) {
		$this->_url = $url;
		$this->_root = dirname(__FILE__) . "/downloads/";
		$this->_videoId = Helper::getVideoId($this->_url);
		$this->_file = $this->_root . $this->_videoId . ".mp4";
	}

	protected function haveVideo() {
		if (!file_exists($this->_file)) {
			$cmd = "youtube-dl -f 17 -o ". $this->_file . " " . $this->_url;
			exec($cmd, $output, $return);

			if ($return != 0) {
				throw new YoutubeDL("Unable to download the track file");	
			}	
		}
	}

	public function convert($fmt = "mp3") {
		$this->haveVideo();
		$this->_converted = $this->_root . $this->_videoId . ".{$fmt}";
		Conversion::To($fmt, $this->_file, $this->_converted);
	}

	public function getUrl() {
		return $_url;
	}

	public function getVideoId() {
		return $this->_videoId;
	}

	public function setDownloadPath($path) {
		$this->_root = $path;
	}

	public function getDownloadPath() {
		return $this->_root;
	}

	public function getFile() {
		return $this->_converted;
	}
}