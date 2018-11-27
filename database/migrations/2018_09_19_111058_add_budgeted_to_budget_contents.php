<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBudgetedToBudgetContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_contents', function($table){
            $table->float('budgeted', 8, 2)->default(sprintf('%.2f', 0));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_contents', function($table){
            $table->dropColumn('budgeted');
        });
    }
}
