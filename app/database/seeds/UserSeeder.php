<?php

class UserSeeder extends Seeder {

    public function run()
    {
        DB::table('user_access')->delete();
        DB::table('users')->delete();
        DB::table('locations')->delete();

        Location::create(array('ID' => 1, 'Location' => 'Udupi-Manipal',
                    'Country' => 'India'));
        Location::create(array('ID' => 2, 'Location' => 'Kolkata',
                    'Country' => 'India'));

        UserAccess::create(array('UserID' => 'Owner1',
        					'Username' => 'vaniprogrammer',
        					'EMail' => 'vaniprogrammer@manaskriti.com',
        					'Pwd' => 'abc',
        					'Active' => 1,

        	));

        User::create(array('UserID' => 'Owner1',
                            'FullName' => 'Vani Programmer',
                            'EMail' => 'vaniprogrammer@manaskriti.com',
                            'Locality' => 'Dashrathnagar',
                            'City' => 'Manipal',
                            'State' => 'Karnataka',
                            'LocationID' => 1
            ));

        UserAccess::create(array('UserID' => 'Owner2',
                            'Username' => 'vikram',
                            'EMail' => 'vikram@manaskriti.com',
                            'Pwd' => 'abc',
                            'Active' => 1,

            ));

        User::create(array('UserID' => 'Owner2',
                            'FullName' => 'Vikram Murarka',
                            'EMail' => 'vikram@manaskriti.com',
                            'Locality' => 'Hastings',
                            'City' => 'Kolkata',
                            'State' => 'West Bengal',
                            'LocationID' => 2
            ));

        UserAccess::create(array('UserID' => 'Borrower1',
                            'Username' => 'vanimanaskriti',
                            'EMail' => 'vanimurarka@manaskriti.com',
                            'Pwd' => 'def',
                            'Active' => 1,
            ));

        User::create(array('UserID' => 'Borrower1',
                            'FullName' => 'Vani Manaskriti',
                            'EMail' => 'vanimurarka@manaskriti.com',
                            'Locality' => 'Dashrathnagar',
                            'City' => 'Manipal',
                            'State' => 'Karnataka'
            ));

    }

}