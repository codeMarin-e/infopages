<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infopages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('site_id');
            $table->unsignedBigInteger('parent_id')->default(0)->index();
            $table->boolean('active')->default(0);
            $table->string('system')->nullable()->unique();;
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infopages');
    }
};
