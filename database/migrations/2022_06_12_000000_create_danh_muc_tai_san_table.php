<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanhMucTaiSanTable extends Migration
{
    public function up()
    {
        Schema::create('danh_muc_tai_san', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc');
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('danh_muc_tai_san');
    }
}