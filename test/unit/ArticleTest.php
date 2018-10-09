<?php
namespace App\Test;
use CPS\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase {

	public function setUp () {
		
		$article = new Article(__DIR__.'/../../data/articles/');
		$list = $article->list();
		print_r($list);die;
		$this->total_articles = count($list);
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

		var_dump(count($list), $this->total_articles);
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
	}
	
	public function testICanitLoadMissingArticle () {
        	$article = new Article(__DIR__.'/../../data/articles/');
        	$load = $article->load('missing-article');
        	$this->assertFalse($load);
    	}
	
}
