<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInnQueryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inn_query', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('INN')->nullable(false)->unique();
            $table->timestamp('query_time')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('response')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inn_query');
    }
}
