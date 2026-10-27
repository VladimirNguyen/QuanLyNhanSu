<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaiSanTable extends Migration
{
    public function up()
    {
        Schema::create('tai_san', function (Blueprint $table) {
            $table->id();
            $table->string('ma_tai_san');
            $table->string('ten_tai_san');
            $table->unsignedBigInteger('danh_muc_tai_san_id');
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
            $table->foreign('danh_muc_tai_san_id')->references('id')->on('danh_muc_tai_san');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tai_san');
    }
}