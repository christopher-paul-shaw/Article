<?php
namespace CPS;

class Article {
	public $path = __DIR__;
	public $extentions = ['md'];
	public $category = false;
	public $update_cache = false;

	public function __construct ($path=false, $extentions=false) {
	
		if($path) {
			$this->path = $path;
		}

		if (is_array($extentions)) {
			$this->extentions = $extentions;
		}

		$this->cacheFile = $this->path.'/cache.dat'; 
	}

	public function load ($file) {

		$summary = '';
		$content = '';
		$date = '';
		
		$file = str_replace(' ','-', $file);
		$file = strtolower($file);

		$this->currentFile = $this->path.'/'.$file;

		if (!file_exists($this->currentFile)) {
			$result = $this->list($file);	
			if($result) {
				$x = reset($result);
				return $x;
	
			}
			return false;
		}

		$fileContent = file_get_contents($this->currentFile);

		if (strstr($file, '__')) {
			$parts = explode('__',$file);
			$date = $parts[0];
			$name = $parts[1];
		}

		$name = isset($name) ? $name : str_replace('/','',$file);
		$name = explode('.',$name)[0];
		$content = $fileContent;

		if (strstr($fileContent, '--PAGE--')) {
			$summary = explode('--PAGE--',$fileContent)[0];
			$content = explode('--PAGE--',$fileContent)[1];
		}
	
		if (strstr($content, '--DATA--')) {
			$parts = explode('--DATA--',$content);
			$content = $parts[0];
			$data = json_decode($parts[1],true);
		}

		$data = [
			'name' => $name,
			'file' => $file,
			'summary' => $summary,
			'content' => $content,
			'raw' => $fileContent,
			'date' => $date,
			'category' => isset($data['category']) ? $data['category'] : 'general',
			'author' => isset($data['author']) ? $data['author'] : 'Anon',
		];

		return $data;
	}


	public function list ($search=false) {
		
		$articles = $this->getCache();
		if (!$articles) {
			$articles = $this->scan();
			$this->setCache($articles);
		}

		foreach ($articles as $i => $a) {
			if ($search) {
				if (!strstr($a['name'],$search)) unset($articles[$i]);
			}
			if ($this->category) {
				if ($a['category'] != $this->category) unset($articles[$i]);
			}
		}
		return $articles;
		

	}

	public function scan () {

		$list = false;

		$files = new \DirectoryIterator($this->path);
		foreach($files as $file) {
			if (!in_array($file->getExtension(),$this->extentions)) continue;
			$filename = $file->getFilename();	
			$article = $this->load($file);
			if(!$article) continue;
			$list[$filename] = $article;
		}
		
		if (is_array($list)) {
			ksort($list);
		}

		return $list;
	}

	public function getCache () {
		if ($this->update_cache) {
			return false;
		}
	
		if (file_exists($this->cacheFile) 
		&& filemtime($this->cacheFile) > (time() - 3600)) {
			return unserialize(file_get_contents($this->cacheFile));
		}
                return false;
		
	}
	
	public function setCache ($articles) {
		file_put_contents($this->cacheFile, serialize($articles));
	}
	
	
		
		
}
