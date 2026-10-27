<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhoaHocTable extends Migration
{
    public function up()
    {
        Schema::create('khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khoa_hoc');
            $table->string('thoi_gian')->nullable();
            $table->text('thong_tin_khoa_hoc')->nullable();
            $table->text('ke_hoach_hoc_tap')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->string('hoc_phi')->nullable();
            $table->integer('trang_thai')->default(1);
            $table->unsignedBigInteger('id_danh_muc')->nullable();
            $table->timestamps();
            $table->foreign('id_danh_muc')->references('id')->on('danh_muc_khoa_hoc');
        });
    }

    public function down()
    {
        Schema::dropIfExists('khoa_hoc');
    }
}