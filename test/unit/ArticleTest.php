<?php
namespace App\Test;
use CPS\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase {

	public function setUp () {
		$this->dir = __DIR__.'/../../data/articles/';
		
		file_put_contents("{$this->dir}test.md",'Test Content');
		file_put_contents("{$this->dir}2018-01-01__test.md",'Test Content');
		file_put_contents("{$this->dir}category__2018-01-02__test.md",'Test Content');

		$article = new Article($this->dir);
		$list = $article->list();
		$this->total_articles = count($list);
		$this->categoriesFile = $this->dir.'categories.json';
	}

	public function tearDown () {

		if (file_exists($this->categoriesFile)) {
			unlink($this->categoriesFile);
		}
	}

    	public function testICanSetUsingConstructor () {
        	$dir = './dir/';
        	$ext = ['a','b'];

        	$article = new Article($dir,$ext);
        	$this->assertEquals($article->path,$dir);
        	$this->assertEquals($article->extentions,$ext);
	}

	public function testICanListArticles () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$list = $article->list();
		$this->assertTrue(count($list) == $this->total_articles);
	}

	public function testICanSearchArticles () {
        	$article = new Article(__DIR__.'/../../data/articles/');
        	$list = $article->list("example");
        	$this->assertTrue(count($list) < $this->total_articles);
	}

	public function testICanLoadArticle () {
        	$article = new Article(__DIR__.'/../../data/articles/');
        	$list = $article->list();
        	$file = reset($list);
        	$load = $article->load($file['file']);
        	$this->assertTrue(is_array($load));
	}

    	public function testICanLoadArticleByName () {
        	$article = new Article(__DIR__.'/../../data/articles/');
        	$list = $article->list();
        	$file = reset($list);
        	$load = $article->load($file['name']);
        	$this->assertTrue(is_array($load));
		$this->assertTrue(strstr($article->currentFile,'.md') !== FALSE);
	}
	
	public function testICanitLoadMissingArticle () {
        	$article = new Article(__DIR__.'/../../data/articles/');
        	$load = $article->load('missing-article');
        	$this->assertFalse($load);
    	}

	public function testICanGetCategortyList () { 

        	$article = new Article(__DIR__.'/../../data/articles/');
        	$categories = $article->getCategories();

		$this->assertTrue(is_array($categories));
		$this->assertTrue(file_exists($this->categoriesFile));

        	$article = new Article(__DIR__.'/../../data/articles/');
        	$categories = $article->getCategories();
		
	}	
	
	public function testICanitGetAFakeCategortyList () { 

		unlink("{$this->dir}test.md");
		unlink("{$this->dir}2018-01-01__test.md");
		unlink("{$this->dir}category__2018-01-02__test.md");

        	$article = new Article(__DIR__.'/../../data/articles/');
        	$categories = $article->getCategories();

		$this->assertFalse($categories);
		
	}	
}
