<?php

class BlogSeeder extends Seeder {

	public function run()
	{
		DB::table('blg_author')->delete();
        DB::table('blg_post')->delete();
        DB::table('blg_post_category')->delete();
        DB::table('blg_post_comment')->delete();

        BlogPost::create(array('PostID' => 1, 'Title' => 'A Simple Voice Talking To And Of God',
                    'Status' => 1, 'Type' => 1, 'WhenPublished' => date('Y-m-d H:i:s')));
	}

}