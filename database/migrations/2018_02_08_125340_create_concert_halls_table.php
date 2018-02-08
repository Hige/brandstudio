<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConcertHallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('concert_halls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->string('slug')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('phone')->nullable();
			$table->string('address')->nullable();
			$table->string('metro')->nullable();
			$table->text('work_time', 65535)->nullable();
			$table->string('url')->nullable();
			$table->integer('rate');
			$table->enum('released', array('0','1'))->nullable()->default('0');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('concert_halls');
	}

}
