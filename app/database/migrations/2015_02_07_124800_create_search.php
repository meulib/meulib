<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
/*		Schema::create('search_tbl', function(Blueprint $table)
		{
			$table->increments('ID');
			$table->string('Phrase', 10000);
			$table->enum('EntityType', array('Book', 'Author', 'Category', 'PseudoCategory'));
			$table->bigInteger('EntityID');

			$table->timestamps();
		});*/

		$connSettings = Config::get('database.connections');
		$tblPrefix = '';
		$tblPrefix = $connSettings['mysql']['prefix'];
		$sql = "CREATE TABLE `".$tblPrefix."search_tbl` ("
				. "`ID` bigint(20) NOT NULL AUTO_INCREMENT,"
				. "`Phrase` varchar(10000) NOT NULL,"
				. "`EntityType` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: BookTitle, 2: BookSubTitle, 3:AuthorName, 4: Category, 5:PseudoCategory',"
				. "`EntityID` bigint(20) NOT NULL,"
				. "`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,"
				. "`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',"
				. "PRIMARY KEY (`ID`),"
				. "FULLTEXT search_idx (Phrase)"
				. ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		//DB::statement($s);
		DB::connection()->getPdo()->exec( $sql );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('search_tbl');
	}

}
