<?php

class UserAccessSeeder extends Seeder {

    public function run()
    {
        DB::table('user_access')->delete();
        DB::table('users')->delete();

        UserAccess::create(array('UserID' => 'OZJM1549672278',
        					'Username' => 'vanimanaskriti',
        					'EMail' => 'vaniprogrammer@manaskriti.com',
        					'Pwd' => '$5$02f6f58c97279$RAulmQCXggcZwm9YIfr.Ne.ASgViMormith8ULKGxvA',
        					'Active' => 1,
        	));

        User::create(array('UserID' => 'OZJM1549672278',
                            'FullName' => 'Vani Programmer',
                            'EMail' => 'vaniprogrammer@manaskriti.com',
                            'Locality' => 'Dashrathnagar',
                            'City' => 'Manipal',
                            'State' => 'Karnataka'
            ));

    }

}