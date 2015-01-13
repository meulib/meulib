<?php

class BookSeeder extends Seeder {

    public function run()
    {
        DB::table('books_flat')->delete();
        DB::table('bookcopies')->delete();
        DB::table('languages')->delete();
        DB::table('categories')->delete();
        DB::table('book_categories')->delete();

        Language::create(array('ID' => 1, 'LanguageEnglish' => 'English',
                    'LanguageNative' => 'English'));
        Language::create(array('ID' => 2, 'LanguageEnglish' => 'Hindi',
                    'LanguageNative' => 'हिन्दी'));

        Category::create(array('ID' => 1,'Category'=>'Comics'));
        Category::create(array('ID' => 2,'Category'=>"Biography"));

        FlatBook::create(array('ID' => 1,
					'Title' => 'Dennis',
					'Author1' => 'Hank Ketcham',
                    'Language1' => 'English',
                    'Language1ID' => 1
        	));
        BookCopy::create(array('ID' => 1,
                    'BookID' => 1,
                    'UserID' => 'Owner1',
                    'LocationID' => 1   // udupi-manipal
            ));
    }

}