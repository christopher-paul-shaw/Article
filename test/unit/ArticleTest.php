<?php
namespace App\Test;
use CPS\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase {

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
		$this->assertTrue(count($list) == 3);
	}

    public function testICanSearchArticles () {
        $article = new Article(__DIR__.'/../../data/articles/');
        $list = $article->list("example");
        $this->assertTrue(count($list) != 3);
    }

    public function testICanLoadArticle () {
        $article = new Article(__DIR__.'/../../data/articles/');
        $list = $article->list();
        $file = array_keys($list)[0];
        $load = $article->load($file);
        $this->assertTrue(is_array($load));
    }

}



