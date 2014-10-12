<?php

class BookSeeder extends Seeder {

    public function run()
    {
        DB::table('books_flat')->delete();
        DB::table('bookcopies')->delete();

        FlatBook::create(array('ID' => 1,
					'Title' => 'Dennis',
					'Author1' => 'Hank Ketcham'
        	));
        BookCopy::create(array('ID' => 1,
                    'BookID' => 1,
                    'UserID' => 'Owner1',
                    'LocationID' => 1   // udupi-manipal
            ));
    }

}