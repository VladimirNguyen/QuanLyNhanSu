<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaiSanConTable extends Migration
{
    public function up()
    {
        Schema::create('tai_san_con', function (Blueprint $table) {
            $table->id();
            $table->string('ma_tai_san_con');
            $table->string('nam_su_dung')->nullable();
            $table->string('thong_so_ky_thuat')->nullable();
            $table->string('xuat_xu')->nullable();
            $table->unsignedBigInteger('id_don_vi')->nullable();
            $table->unsignedBigInteger('id_tai_san')->nullable();
            $table->string('nguon_kinh_phi')->nullable();
            $table->string('nguyen_gia')->nullable();
            $table->string('thoi_gian_khau_hao')->nullable();
            $table->string('gia_tri_con_lai')->nullable();
            $table->string('thoi_han_bao_hanh')->nullable();
            $table->integer('trang_thai')->default(4);
            $table->integer('tinh_trang_kk')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tai_san_con');
    }
}