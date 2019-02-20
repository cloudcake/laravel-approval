<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('modifiable_id')->nullable();
            $table->string('modifiable_type')->nullable();
            $table->integer('modifier_id')->nullable();
            $table->string('modifier_type')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_update')->default(true);
            $table->integer('approvers_required')->default(1);
            $table->integer('disapprovers_required')->default(1);
            $table->string('md5');
            $table->json('modifications');
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
        Schema::dropIfExists('modifications');
    }
}
