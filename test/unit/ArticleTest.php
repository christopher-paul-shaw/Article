<?php
namespace App\Test;
use CPS\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase {

	public function setUp () {
		$this->dir = __DIR__.'/../../data/articles/';
		
		$test = <<<HEREDOC
		This is the Summary
		--PAGE--
		This is the content
		--DATA--
		{
			"category": "test",
			"author": "Chris Shaw"
		}
HEREDOC;
		file_put_contents("{$this->dir}cache.dat",'');
		file_put_contents("{$this->dir}categories.dat",'');
		file_put_contents("{$this->dir}test.md",$test);
		file_put_contents("{$this->dir}2018-01-01__test.md",'Test Content');
		file_put_contents("{$this->dir}category__2018-01-02__test.md",'Test Content');
		
		$article = new Article($this->dir);
		$list = $article->list();
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
		$this->assertTrue(strstr($load['file'],'.md') !== FALSE);
	}
	
	public function testICantLoadMissingArticle () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$load = $article->load('missing-article');
		$this->assertFalse($load);
	}

	public function testICanSetCache () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$load = $article->list();

		$cacheFile = file_get_contents(__DIR__.'/../../data/articles/cache.dat');
		$this->assertTrue(!empty($cacheFile));
	}

	public function testICantSetCacheWhenDisabled() {	
		file_put_contents(__DIR__.'/../../data/articles/cache.dat','');
		$article = new Article(__DIR__.'/../../data/articles/');
		$article->disable_cache = true;
		$load = $article->list();

		$cacheFile = file_get_contents(__DIR__.'/../../data/articles/cache.dat');
		$this->assertTrue(empty($cacheFile));
	}

	public function testICanClearCache () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$article->clearCache();

		$cacheFile = file_get_contents(__DIR__.'/../../data/articles/cache.dat');
		$this->assertTrue(empty($cacheFile));
	}

	public function testICantClearCacheWhenDisabled () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$article->disable_cache = true;
		$article->clearCache();

		$cacheFile = file_get_contents(__DIR__.'/../../data/articles/cache.dat');
		$this->assertTrue(!empty($cacheFile));
	}

	public function testICanGetCategoryList () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$categories = $article->getCategoryList();
		$this->assertTrue(is_array($categories));
	}

	public function testICanLimitByCategory () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$article->category = 'general';
		$list = $article->list();
		$count = count($list);
		$this->assertTrue($count > 0 && $count < $this->total_articles);
	}
	
	public function testICanCreateArticle () {
		$article = new Article(__DIR__.'/../../data/articles/');
		$filename = $article->create (
			'create_testfile',
			'this s some content',
			'2018-01-01',
			[
				'category' => 'test',
				'author' => 'test'
			]
		);
		
		$result = $article->load($filename);
		$this->assertTrue($result);
	}

	public function testICanDeleteArticle () {
		$article = new Article(__DIR__.'/../../data/articles/');
   		$list = $article->list();
   		$file = reset($list);
   		$filename = $file['file'];
		
		$article->delete($filename);
		$result = $article->load($filename);
		$this->assertFalse($result);
	}
		
}
