<?php
namespace CPS;

class Article {
	public $path = __DIR__;
	public $extentions = ['md'];

	public function __construct ($path=false, $extentions=false) {

		if($path) {
			$this->path = $path;
		}

		if (is_array($extentions)) {
			$this->extentions = $extentions;
		}

	}

	public function load ($file) {

		$file = str_replace(' ','-', $file);
		$file = strtolower($file);

		$currentFile = $this->path.'/'.$file;

		foreach ($this->extentions as $extention) {
			if(@file_get_contents($currentFile.'.'.$extention)!==false){
				$currentFile.='.'.$extention;
				break;
			}
		}

		$fileContent = @file_get_contents($currentFile);
		if($fileContent === false) return false;

		if (strstr($file, '__')) {
			$parts = explode('__',$file);
			$date = $parts[0];
			$name = $parts[1];
		}

		$name="";
		$name = $name ? $name : str_replace('/','',$file);
		$name = str_replace('.'.$extention,'',$name);

		$data = [
			'name' => $name,
			'file' => $file,
			'content' => $fileContent,
			'date' => isset($date) ? $date : false,
		];

		return $data;
	}

	public function list ($search=false) {

		$files = new \DirectoryIterator($this->path);
		foreach($files as $file) {

			if (!in_array($file->getExtension(),$this->extentions)) continue;

			$filename = $file->getFilename();

			if ($search && !strstr($filename, $search)) continue;

			$article = $this->load($file);
			if(!$article) continue;

			$this->files[$filename] = $article;
		}

		krsort($this->files);
		return $this->files;
	}

}
