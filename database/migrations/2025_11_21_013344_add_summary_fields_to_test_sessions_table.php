<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->unsignedInteger('answered_count')->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedTinyInteger('accuracy')->default(0); // 0–100 %

            // flag supaya user boleh tes ulang (di-set oleh admin)
            $table->boolean('can_retake')->default(false);

            // optional: kapan sesi selesai
            if (! Schema::hasColumn('test_sessions', 'finished_at')) {
                $table->timestamp('finished_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'answered_count',
                'correct_count',
                'wrong_count',
                'accuracy',
                'can_retake',
                'finished_at',
            ]);
        });
    }
};
