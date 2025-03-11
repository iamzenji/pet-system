<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('breed');
            $table->string('gender');
            $table->string('color');
            $table->string('size');
            $table->integer('age');
            $table->decimal('weight', 5, 2);
            $table->string('image')->nullable();
            $table->string('temperament')->nullable();
            $table->string('health_status')->nullable();
            $table->boolean('spayed_neutered')->nullable();
            $table->boolean('vaccination_status')->nullable();
            $table->string('good_with')->nullable();
            $table->string('adoption_status')->default('Available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pets');
    }
};
