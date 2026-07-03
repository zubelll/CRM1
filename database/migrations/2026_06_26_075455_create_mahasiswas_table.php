<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->string('angkatan', 4);
            $table->integer('semester');
            $table->enum('status', ['aktif','cuti','drop_out','tanpa_keterangan'])->default('aktif');
            $table->string('no_whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->integer('sks_tempuh')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
