# Summary
This class interacts with articles stored within files on disk, and allows listing, searching and viewing of the files.

# Usage

    $article = new Article();
    
    # List All Articles
    $list = $article->list();

    # Search Articles
    $search = $article->list("Your Search Term");

    # Load (View) 
    $view = $article->load("your-file");
    
    # Get List Of Categories
    $categories = $article->getCategories();

# Test
As features are added, there will be new tests to prove they work as intended. 
You can run the tests yourself using the following command.

    vendor/bin/phpunit test
