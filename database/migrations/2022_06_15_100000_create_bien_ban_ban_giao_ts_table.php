<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBienBanBanGiaoTsTable extends Migration
{
    public function up()
    {
        Schema::create('bien_ban_ban_giao_ts', function (Blueprint $table) {
            $table->id();
            $table->date('ngay_lap')->nullable();
            $table->string('ho_ten_nguoi_giao')->nullable();
            $table->string('chuc_danh_nguoi_giao')->nullable();
            $table->string('bo_phan_nguoi_giao')->nullable();
            $table->string('ho_ten_nguoi_nhan')->nullable();
            $table->string('chuc_danh_nguoi_nhan')->nullable();
            $table->string('bo_phan_nguoi_nhan')->nullable();
            $table->string('so_luong_bien_ban')->nullable();
            $table->unsignedBigInteger('id_don_vi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bien_ban_ban_giao_ts');
    }
}