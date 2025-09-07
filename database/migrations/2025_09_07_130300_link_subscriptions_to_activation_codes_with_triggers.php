<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) العمود والفهارس والـFK
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'activation_code_id')) {
                $table->unsignedBigInteger('activation_code_id')->nullable()->after('user_id');
            }
        });

        // فهرس فريد + فهرس مساعد
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->unique('activation_code_id', 'uq_sub_activation_code');
                $table->index(['user_id','status'], 'idx_sub_user_status');
            });
        } catch (\Throwable $e) { /* موجود مسبقًا */ }

        // FK
        Schema::table('subscriptions', function (Blueprint $table) {
            try { $table->dropForeign('subscriptions_activation_code_id_foreign'); } catch (\Throwable $e) {}
            $table->foreign('activation_code_id')->references('id')->on('activation_codes')->onDelete('set null');
        });

        // 2) إسقاط القوادح القديمة
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_bi_code;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_ai_code;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_bu_code;");

        // 3) BEFORE INSERT — جميع DECLARE في بداية البلوك + HANDLER لـ NOT FOUND
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_subscriptions_bi_code
BEFORE INSERT ON subscriptions
FOR EACH ROW
BEGIN
    -- المتغيّرات يجب أن تُصرّح هنا في أعلى البلوك (مطلب MariaDB)
    DECLARE v_plan_id BIGINT UNSIGNED DEFAULT NULL;
    DECLARE v_univ_id BIGINT UNSIGNED DEFAULT NULL;
    DECLARE v_college_id BIGINT UNSIGNED DEFAULT NULL;
    DECLARE v_major_id BIGINT UNSIGNED DEFAULT NULL;

    DECLARE v_duration INT DEFAULT NULL;
    DECLARE v_start_policy VARCHAR(20) DEFAULT NULL;
    DECLARE v_starts_on DATE DEFAULT NULL;
    DECLARE v_valid_from DATETIME DEFAULT NULL;
    DECLARE v_valid_until DATETIME DEFAULT NULL;
    DECLARE v_status VARCHAR(20) DEFAULT NULL;

    DECLARE v_user_univ BIGINT UNSIGNED DEFAULT NULL;
    DECLARE v_user_col BIGINT UNSIGNED DEFAULT NULL;
    DECLARE v_user_major BIGINT UNSIGNED DEFAULT NULL;

    DECLARE v_max_red TINYINT UNSIGNED DEFAULT 1;
    DECLARE v_used TINYINT UNSIGNED DEFAULT 0;

    DECLARE v_not_found INT DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_not_found = 1;

    IF NEW.activation_code_id IS NOT NULL THEN
        -- جلب بيانات الكود
        SET v_not_found = 0;
        SELECT ac.plan_id, ac.university_id, ac.college_id, ac.major_id,
               ac.duration_days, ac.start_policy, ac.starts_on, ac.valid_from, ac.valid_until,
               ac.status, ac.max_redemptions, ac.redemptions_count
        INTO   v_plan_id, v_univ_id, v_college_id, v_major_id,
               v_duration, v_start_policy, v_starts_on, v_valid_from, v_valid_until,
               v_status, v_max_red, v_used
        FROM activation_codes ac
        WHERE ac.id = NEW.activation_code_id
        LIMIT 1;

        IF v_not_found = 1 OR v_plan_id IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code not found';
        END IF;

        -- صلاحية الكود
        IF v_status <> 'active' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code is not active';
        END IF;

        IF v_valid_from IS NOT NULL AND NOW() < v_valid_from THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code not yet valid';
        END IF;

        IF v_valid_until IS NOT NULL AND NOW() > v_valid_until THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code expired';
        END IF;

        IF v_used >= v_max_red THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code already redeemed';
        END IF;

        -- نطاق الجامعة/الكلية/التخصص
        SET v_not_found = 0;
        SELECT u.university_id, u.college_id, u.major_id
        INTO   v_user_univ, v_user_col, v_user_major
        FROM users u
        WHERE u.id = NEW.user_id
        LIMIT 1;

        IF v_not_found = 1 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User not found for subscription';
        END IF;

        IF v_univ_id IS NOT NULL AND (v_user_univ IS NULL OR v_user_univ <> v_univ_id) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code restricted to another university';
        END IF;

        IF v_college_id IS NOT NULL AND (v_user_col IS NULL OR v_user_col <> v_college_id) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code restricted to another college';
        END IF;

        IF v_major_id IS NOT NULL AND (v_user_major IS NULL OR v_user_major <> v_major_id) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code restricted to another major';
        END IF;

        -- فرض الخطة وبداية/نهاية الاشتراك
        SET NEW.plan_id = v_plan_id;

        IF v_start_policy = 'on_redeem' THEN
            IF NEW.started_at IS NULL THEN SET NEW.started_at = NOW(); END IF;
        ELSEIF v_start_policy = 'fixed_start' THEN
            IF v_starts_on IS NULL THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Activation code has fixed_start policy but no starts_on';
            END IF;
            SET NEW.started_at = v_starts_on;
        END IF;

        IF NEW.ends_at IS NULL THEN
            SET NEW.ends_at = DATE_ADD(NEW.started_at, INTERVAL v_duration DAY);
        END IF;

        SET NEW.status = 'active';
        SET NEW.auto_renew = 0;
    END IF;
END;
SQL);

        // AFTER INSERT — تعليم الكود كمسترد
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_subscriptions_ai_code
AFTER INSERT ON subscriptions
FOR EACH ROW
BEGIN
    IF NEW.activation_code_id IS NOT NULL THEN
        UPDATE activation_codes ac
        SET ac.redemptions_count = ac.redemptions_count + 1,
            ac.status = CASE
                           WHEN ac.redemptions_count + 1 >= ac.max_redemptions THEN 'redeemed'
                           ELSE ac.status
                        END,
            ac.redeemed_by_user_id = NEW.user_id,
            ac.redeemed_at = NOW(),
            ac.updated_at = NOW()
        WHERE ac.id = NEW.activation_code_id;
    END IF;
END;
SQL);

        // BEFORE UPDATE — منع تغيير الكود بعد الإنشاء
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_subscriptions_bu_code
BEFORE UPDATE ON subscriptions
FOR EACH ROW
BEGIN
    IF OLD.activation_code_id IS NOT NULL AND NEW.activation_code_id <> OLD.activation_code_id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'activation_code_id cannot be changed after creation';
    END IF;
END;
SQL);
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_bu_code;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_ai_code;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_subscriptions_bi_code;");

        Schema::table('subscriptions', function (Blueprint $table) {
            try { $table->dropForeign(['activation_code_id']); } catch (\Throwable $e) {}
            try { $table->dropUnique('uq_sub_activation_code'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_sub_user_status'); } catch (\Throwable $e) {}
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'activation_code_id')) {
                $table->dropColumn('activation_code_id');
            }
        });
    }
};
