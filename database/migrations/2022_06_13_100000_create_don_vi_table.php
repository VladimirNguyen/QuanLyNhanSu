<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonViTable extends Migration
{
    public function up()
    {
        Schema::create('don_vi', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_vi');
            $table->string('ten_don_vi');
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('don_vi');
    }
}