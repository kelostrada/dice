<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 21, 10)->default(100);
        });

        Schema::create('bets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('value', 21, 10);
            $table->decimal('profit', 21, 10);
            $table->decimal('payout', 8, 3);
            $table->decimal('roll', 5, 2);

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('bets');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance']);
        });
    }
}
