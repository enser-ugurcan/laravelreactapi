<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('keyword');
            $table->string('description');
            $table->string('company');
            $table->string('address');
            $table->string('phone');
            $table->string('fax');
            $table->string('smtpserver');
            $table->string('smtpemail');
            $table->string('smtppassword');
            $table->integer('smtpport');
            $table->string('instagram');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('youtube');
            $table->text('aboutus');
            $table->text('references');
            $table->timestamps();
        });
    
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
