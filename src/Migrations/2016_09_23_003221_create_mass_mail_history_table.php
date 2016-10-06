<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMassMailHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('mass_mail_history') ) {
            Schema::create( 'mass_mail_history', function(Blueprint $table) {
                $table->increments('id');
                $table->string('subject');
                $table->string('mailing_list');
                $table->json('params');
                $table->string('archive_link')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'mass_mail_history' );
    }
}
