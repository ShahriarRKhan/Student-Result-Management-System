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
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('id')->constrained('teachers')->nullOnDelete();
            }

            if (!Schema::hasColumn('results', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('grade');
            }

            if (!Schema::hasColumn('results', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('results', 'is_published')) {
                $table->dropColumn('is_published');
            }

            if (Schema::hasColumn('results', 'teacher_id')) {
                $table->dropConstrainedForeignId('teacher_id');
            }
        });
    }
};
