<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoFieldsNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo_fields_nodes', function(Blueprint $table)
		{
			$table->unsignedInteger('seo_fields_id');
			$table->char('language_id', 2);
			$table->string('title');
			$table->text('description');
			$table->text('keywords');
			$table->timestamps();

			$table->primary(['seo_fields_id', 'language_id']);

			$table->foreign('seo_fields_id')
				->references('id')
				->on('seo_fields')
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
		Schema::table('seo_fields_nodes', function(Blueprint $table)
		{
			$table->dropForeign('seo_fields_nodes_seo_fields_id_foreign');
		});
		Schema::drop('seo_fields_nodes');
	}

}
