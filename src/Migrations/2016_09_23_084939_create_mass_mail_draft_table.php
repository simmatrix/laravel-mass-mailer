<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMassMailDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('mass_mail_draft') ) {
            Schema::create( 'mass_mail_draft', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->json('params');
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
        Schema::dropIfExists( 'mass_mail_draft' );
    }
}
