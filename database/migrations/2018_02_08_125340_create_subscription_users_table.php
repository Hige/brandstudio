<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index('event_id');
			$table->string('name');
			$table->string('email');
			$table->string('phone')->nullable();
			$table->enum('gender', array('m','f'))->nullable();
			$table->dateTime('birthday')->nullable();
			$table->string('ip', 15)->default('127.0.0.1');
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
		Schema::drop('subscription_users');
	}

}
