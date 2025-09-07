<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activation_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('code', 64)->unique();

            $table->unsignedBigInteger('plan_id');

            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('college_id')->nullable();
            $table->unsignedBigInteger('major_id')->nullable();

            $table->unsignedInteger('duration_days')->default(365);
            $table->enum('start_policy', ['on_redeem','fixed_start'])->default('on_redeem');
            $table->date('starts_on')->nullable();

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            $table->unsignedTinyInteger('max_redemptions')->default(1);
            $table->unsignedTinyInteger('redemptions_count')->default(0);

            $table->enum('status', ['active','redeemed','expired','disabled'])->default('active');

            $table->unsignedBigInteger('redeemed_by_user_id')->nullable();
            $table->timestamp('redeemed_at')->nullable();

            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->timestamps();

            $table->index(['status', 'valid_until']);
            $table->index(['university_id', 'status']);

            $table->foreign('batch_id')->references('id')->on('activation_code_batches')->onDelete('set null');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');

            $table->foreign('university_id')->references('id')->on('universities')->onDelete('set null');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('set null');

            $table->foreign('redeemed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });

        // قيود الاتساق الهرمي (جامعة ← كلية ← تخصص)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acode_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acode_bu;");

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_acode_bi
BEFORE INSERT ON activation_codes
FOR EACH ROW
BEGIN
    -- إذا حُددت كلية يجب أن تتبع الجامعة المحددة (إن وُجدت جامعة)
    IF NEW.college_id IS NOT NULL AND NEW.university_id IS NOT NULL AND NOT EXISTS (
        SELECT 1 FROM colleges c WHERE c.id = NEW.college_id AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activation_codes: college_id does not belong to university_id';
    END IF;

    -- إذا حُدد تخصص يجب أن يتبع الكلية/الجامعة (إن وُجدا)
    IF NEW.major_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM majors m
        JOIN colleges c ON c.id = m.college_id
        WHERE m.id = NEW.major_id
          AND (NEW.college_id IS NULL OR m.college_id = NEW.college_id)
          AND (NEW.university_id IS NULL OR c.university_id = NEW.university_id)
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activation_codes: major_id does not belong to provided college/university';
    END IF;
END;
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_acode_bu
BEFORE UPDATE ON activation_codes
FOR EACH ROW
BEGIN
    IF NEW.college_id IS NOT NULL AND NEW.university_id IS NOT NULL AND NOT EXISTS (
        SELECT 1 FROM colleges c WHERE c.id = NEW.college_id AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activation_codes: college_id does not belong to university_id';
    END IF;

    IF NEW.major_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM majors m
        JOIN colleges c ON c.id = m.college_id
        WHERE m.id = NEW.major_id
          AND (NEW.college_id IS NULL OR m.college_id = NEW.college_id)
          AND (NEW.university_id IS NULL OR c.university_id = NEW.university_id)
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activation_codes: major_id does not belong to provided college/university';
    END IF;
END;
SQL);
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::unprepared("DROP TRIGGER IF EXISTS trg_acode_bi;");
        \Illuminate\Support\Facades\DB::unprepared("DROP TRIGGER IF EXISTS trg_acode_bu;");
        Schema::dropIfExists('activation_codes');
    }
};
