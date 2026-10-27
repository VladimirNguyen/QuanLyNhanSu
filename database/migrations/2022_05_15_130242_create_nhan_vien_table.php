<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNhanVienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhan_vien', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->date('ngay_sinh');
            $table->string('gioi_tinh');
            $table->string('dia_chi');
            $table->string('so_dien_thoai')->unique();
            $table->string('so_chung_minh')->unique();
            $table->string('anh_cmt_truoc');
            $table->string('anh_cmt_sau');
            $table->string('email');
            $table->date('ngay_gia_nhap');
            $table->date('ngay_nghi_lam')->nullable();
            $table->integer('id_phong_ban')->nullable();
            $table->integer('id_chuc_vu')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhan_vien');
    }
}
