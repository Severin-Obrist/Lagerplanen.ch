<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBidCollumnFromBudgetList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
      {
          Schema::table('budget_list', function($table) {
             $table->dropColumn('bid');
          });
      }

      public function down()
      {
          Schema::table('budget_list', function($table) {
             $table->integer('bid');
          });
      }
}
