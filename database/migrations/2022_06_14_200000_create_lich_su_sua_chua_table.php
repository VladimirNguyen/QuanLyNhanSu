<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLichSuSuaChuaTable extends Migration
{
    public function up()
    {
        Schema::create('lich_su_sua_chua', function (Blueprint $table) {
            $table->id();
            $table->date('ngay_sua_chua')->nullable();
            $table->string('noi_dung')->nullable();
            $table->string('nguyen_nhan')->nullable();
            $table->string('chi_phi')->nullable();
            $table->string('nguon_chi')->nullable();
            $table->unsignedBigInteger('id_tai_san_con')->nullable();
            $table->timestamps();
            $table->foreign('id_tai_san_con')->references('id')->on('tai_san_con');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_sua_chua');
    }
}