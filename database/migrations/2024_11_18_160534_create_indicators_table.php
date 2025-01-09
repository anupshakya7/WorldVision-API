<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->nullable();
            $table->integer('domain_id')->nullable();
            $table->string('variablename_long');
            $table->string('variablename');
            $table->text('vardescription')->nullable();
            $table->string('varunits')->nullable();
            $table->tinyInteger('is_more_better')->nullable();
            $table->string('transformation')->nullable();
            $table->decimal('lower', 3, 1)->nullable();
            $table->integer('upper')->nullable();
            $table->string('sourcelinks')->nullable();
            $table->string('subnational')->nullable();
            $table->string('national')->nullable();
            $table->string('imputation')->nullable();
            $table->integer('level')->nullable()->comment('0=Domain
                                                            1=Indicator');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
};
