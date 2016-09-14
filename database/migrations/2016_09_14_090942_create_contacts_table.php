<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('number');
            $table->timestamps();
        });

        DB::table('contacts')->insert([
            ['first_name' => 'Demo', 'last_name' => 'Test', 'number' => '+912563258412'],
            ['first_name' => 'Rupesh', 'last_name' => 'Pandey', 'number' => '+918377988697']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
