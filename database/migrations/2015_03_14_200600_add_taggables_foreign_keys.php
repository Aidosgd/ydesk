<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaggablesForeignKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('taggables', function(Blueprint $table)
		{
			$table->foreign('tag_id')
				->references('id')
				->on('tags')
				->onUpdate('CASCADE')
				->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('taggables', function(Blueprint $table)
		{
			$table->dropForeign('taggables_tag_id_foreign');
		});
	}

}
