<?php

class BookSeeder extends Seeder {

    public function run()
    {
        DB::table('books_flat')->delete();
        DB::table('bookcopies')->delete();

        /*FlatBook::create(array('ID' => 1,
					'Title' => 'Dennis',
					'Author1' => 'Hank Ketcham'
        	));
        FlatBook::create(array('ID' => 2,
					'Title' => 'Spiritual Midwifery',
					'Author1' => 'Ina May Gaskin'
        	));*/
    }

}