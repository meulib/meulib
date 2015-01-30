<?php

class TransactionSeeder extends Seeder {

    public function run()
    {
        DB::table('messages2')->delete();
        DB::table('transactions_active')->delete();
        DB::table('transactions_archived')->delete();
        DB::table('transactions_history')->delete();
        DB::table('transaction_messages')->delete();
    }

}