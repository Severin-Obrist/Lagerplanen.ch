<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBudgetRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_relations', function (Blueprint $table) {
            $table->increments('id'); //simple way to id each entry
            $table->integer('bid'); //Id of the referenced budget
            $table->integer('pid'); //Id of the referenced user
            $table->boolean('isCreator'); //tells if the referenced User is the creator of the referenced budget
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_relations');
    }
}
