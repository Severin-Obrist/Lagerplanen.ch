<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameToBudgetRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_relations', function($table){
            $table->string('budget_name');
        });

        Schema::table('budget_list', function($table){
            $table->string('budget_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_relations', function($table){
            $table->dropColumn('budget_name');
        });

        Schema::table('budget_list', function($table){
            $table->dropColumn('budget_name');
        });
    }
}
