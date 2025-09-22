<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
ALTER TABLE `academic_calendars` ADD PRIMARY KEY (`id`),
  ADD KEY `academic_calendars_university_id_is_active_starts_on_index` (`university_id`,`is_active`,`starts_on`);
ALTER TABLE `academic_terms` ADD PRIMARY KEY (`id`),
  ADD KEY `academic_terms_calendar_id_is_active_starts_on_index` (`calendar_id`,`is_active`,`starts_on`);
ALTER TABLE `activation_codes` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activation_codes_code_unique` (`code`),
  ADD KEY `activation_codes_status_valid_until_index` (`status`,`valid_until`),
  ADD KEY `activation_codes_university_id_status_index` (`university_id`,`status`),
  ADD KEY `activation_codes_batch_id_foreign` (`batch_id`),
  ADD KEY `activation_codes_plan_id_foreign` (`plan_id`),
  ADD KEY `activation_codes_college_id_foreign` (`college_id`),
  ADD KEY `activation_codes_major_id_foreign` (`major_id`),
  ADD KEY `activation_codes_redeemed_by_user_id_foreign` (`redeemed_by_user_id`),
  ADD KEY `activation_codes_created_by_admin_id_foreign` (`created_by_admin_id`);
ALTER TABLE `activation_code_batches` ADD PRIMARY KEY (`id`),
  ADD KEY `activation_code_batches_plan_id_university_id_index` (`plan_id`,`university_id`),
  ADD KEY `activation_code_batches_university_id_foreign` (`university_id`),
  ADD KEY `activation_code_batches_college_id_foreign` (`college_id`),
  ADD KEY `activation_code_batches_major_id_foreign` (`major_id`),
  ADD KEY `activation_code_batches_created_by_admin_id_foreign` (`created_by_admin_id`);
ALTER TABLE `admins` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);
ALTER TABLE `assets` ADD PRIMARY KEY (`id`),
  ADD KEY `assets_material_id_foreign` (`material_id`),
  ADD KEY `assets_device_id_foreign` (`device_id`),
  ADD KEY `assets_doctor_id_foreign` (`doctor_id`),
  ADD KEY `assets_program_id_foreign` (`program_id`),
  ADD KEY `assets_published_by_admin_id_foreign` (`published_by_admin_id`),
  ADD KEY `assets_category_material_id_device_id_doctor_id_is_active_index` (`category`,`material_id`,`device_id`,`doctor_id`,`is_active`),
  ADD KEY `idx_assets_pub` (`status`,`is_active`,`published_at`),
  ADD KEY `idx_assets_disc_prog` (`discipline_id`,`program_id`),
  ADD KEY `idx_assets_feed` (`status`,`is_active`,`discipline_id`,`program_id`,`created_at`),
  ADD KEY `ix_assets_scope` (`scope`);
ALTER TABLE `asset_public_major` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_apm_asset_major` (`asset_id`,`public_major_id`),
  ADD UNIQUE KEY `uk_apm_primary_one_per_asset` (`primary_asset_id`),
  ADD KEY `ix_apm_major_asset` (`public_major_id`,`asset_id`),
  ADD KEY `ix_apm_asset_primary` (`asset_id`,`is_primary`),
  ADD KEY `ix_apm_priority` (`priority`);
ALTER TABLE `banners` ADD PRIMARY KEY (`id`),
  ADD KEY `banners_is_active_index` (`is_active`),
  ADD KEY `banners_starts_at_index` (`starts_at`),
  ADD KEY `banners_ends_at_index` (`ends_at`),
  ADD KEY `banners_sort_order_index` (`sort_order`);
ALTER TABLE `blogs` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`),
  ADD KEY `blogs_doctor_id_foreign` (`doctor_id`),
  ADD KEY `blogs_status_index` (`status`),
  ADD KEY `blogs_published_at_index` (`published_at`),
  ADD KEY `blogs_university_id_doctor_id_index` (`university_id`,`doctor_id`);
ALTER TABLE `cache` ADD PRIMARY KEY (`key`);
ALTER TABLE `cache_locks` ADD PRIMARY KEY (`key`);
ALTER TABLE `colleges` ADD PRIMARY KEY (`id`),
  ADD KEY `colleges_branch_id_index` (`branch_id`);
ALTER TABLE `complaints` ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_user_id_status_severity_index` (`user_id`,`status`,`severity`),
  ADD KEY `complaints_type_created_at_index` (`type`,`created_at`),
  ADD KEY `complaints_target_type_target_id_index` (`target_type`,`target_id`),
  ADD KEY `complaints_assigned_admin_id_foreign` (`assigned_admin_id`);
ALTER TABLE `contents` ADD PRIMARY KEY (`id`),
  ADD KEY `contents_college_id_foreign` (`college_id`),
  ADD KEY `contents_major_id_foreign` (`major_id`),
  ADD KEY `contents_material_id_foreign` (`material_id`),
  ADD KEY `contents_doctor_id_foreign` (`doctor_id`),
  ADD KEY `contents_published_by_admin_id_foreign` (`published_by_admin_id`),
  ADD KEY `contents_scope_type_is_active_index` (`type`,`is_active`),
  ADD KEY `idx_contents_pub` (`status`,`is_active`,`published_at`),
  ADD KEY `idx_contents_scope_keys` (`university_id`,`college_id`,`major_id`,`status`,`is_active`),
  ADD KEY `idx_contents_feed` (`status`,`is_active`,`university_id`,`college_id`,`major_id`,`created_at`);
ALTER TABLE `content_device` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content_device_content_id_device_id_unique` (`content_id`,`device_id`),
  ADD KEY `content_device_device_id_foreign` (`device_id`);
ALTER TABLE `countries` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_iso2_unique` (`iso2`);
ALTER TABLE `devices` ADD PRIMARY KEY (`id`),
  ADD KEY `devices_material_id_is_active_index` (`material_id`,`is_active`);
ALTER TABLE `disciplines` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_disc_name` (`name`);
ALTER TABLE `doctors` ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_university_id_index` (`university_id`),
  ADD KEY `doctors_college_id_index` (`college_id`),
  ADD KEY `doctors_major_id_index` (`major_id`),
  ADD KEY `doctors_branch_id_index` (`branch_id`);
ALTER TABLE `doctor_major` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_major_doctor_id_major_id_unique` (`doctor_id`,`major_id`),
  ADD KEY `doctor_major_major_id_foreign` (`major_id`);
ALTER TABLE `email_verification_tokens` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_verification_tokens_token_unique` (`token`),
  ADD KEY `email_verification_tokens_email_index` (`email`),
  ADD KEY `email_verification_tokens_expires_at_index` (`expires_at`);
ALTER TABLE `failed_jobs` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);
ALTER TABLE `jobs` ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);
ALTER TABLE `job_batches` ADD PRIMARY KEY (`id`);
ALTER TABLE `majors` ADD PRIMARY KEY (`id`),
  ADD KEY `majors_college_id_index` (`college_id`);
ALTER TABLE `major_program` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_major_program` (`major_id`,`program_id`),
  ADD KEY `idx_mp_major` (`major_id`),
  ADD KEY `idx_mp_program` (`program_id`);
ALTER TABLE `materials` ADD PRIMARY KEY (`id`),
  ADD KEY `materials_university_id_foreign` (`university_id`),
  ADD KEY `materials_college_id_foreign` (`college_id`),
  ADD KEY `materials_major_id_foreign` (`major_id`),
  ADD KEY `materials_scope_university_id_college_id_major_id_index` (`scope`,`university_id`,`college_id`,`major_id`),
  ADD KEY `idx_materials_level` (`level`);
ALTER TABLE `material_term` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_material_term` (`material_id`,`term_id`),
  ADD KEY `fk_mterm_term` (`term_id`);
ALTER TABLE `migrations` ADD PRIMARY KEY (`id`);
ALTER TABLE `notifications` ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_type_created_at_index` (`user_id`,`type`,`created_at`),
  ADD KEY `notifications_read_at_index` (`read_at`),
  ADD KEY `notifications_content_id_foreign` (`content_id`),
  ADD KEY `notifications_asset_id_foreign` (`asset_id`);
ALTER TABLE `password_reset_tokens` ADD PRIMARY KEY (`email`);
ALTER TABLE `personal_access_tokens` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);
ALTER TABLE `plans` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_code_unique` (`code`),
  ADD KEY `idx_plans_active_cycle` (`is_active`,`billing_cycle`);
ALTER TABLE `plan_features` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_plan_feature_key` (`plan_id`,`feature_key`);
ALTER TABLE `programs` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_prog_disc_name` (`discipline_id`,`name`),
  ADD KEY `programs_discipline_id_is_active_index` (`discipline_id`,`is_active`);
ALTER TABLE `public_colleges` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_public_colleges_name` (`name`),
  ADD UNIQUE KEY `uk_public_colleges_slug` (`slug`),
  ADD KEY `ix_pc_status` (`status`);
ALTER TABLE `public_majors` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_public_majors_college_name` (`public_college_id`,`name`),
  ADD UNIQUE KEY `uk_public_majors_slug` (`slug`),
  ADD KEY `ix_public_majors_college` (`public_college_id`),
  ADD KEY `ix_public_majors_name` (`name`),
  ADD KEY `ix_pm_status` (`status`);
ALTER TABLE `sessions` ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);
ALTER TABLE `student_requests` ADD PRIMARY KEY (`id`),
  ADD KEY `student_requests_user_id_status_priority_index` (`user_id`,`status`,`priority`),
  ADD KEY `student_requests_category_created_at_index` (`category`,`created_at`),
  ADD KEY `student_requests_assigned_admin_id_foreign` (`assigned_admin_id`),
  ADD KEY `student_requests_material_id_foreign` (`material_id`),
  ADD KEY `student_requests_content_id_foreign` (`content_id`);
ALTER TABLE `subscriptions` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sub_activation_code` (`activation_code_id`),
  ADD KEY `subscriptions_status_index` (`status`),
  ADD KEY `subscriptions_started_at_index` (`started_at`),
  ADD KEY `subscriptions_ends_at_index` (`ends_at`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`),
  ADD KEY `idx_sub_user_status` (`user_id`,`status`);
ALTER TABLE `universities` ADD PRIMARY KEY (`id`),
  ADD KEY `universities_name_idx` (`name`),
  ADD KEY `idx_univ_country` (`country_id`);
ALTER TABLE `university_branches` ADD PRIMARY KEY (`id`),
  ADD KEY `ix_branch_univ_active` (`university_id`,`is_active`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_student_number_unique` (`student_number`),
  ADD KEY `users_college_id_foreign` (`college_id`),
  ADD KEY `users_major_id_foreign` (`major_id`),
  ADD KEY `users_university_id_college_id_major_id_index` (`university_id`,`college_id`,`major_id`),
  ADD KEY `idx_user_country` (`country_id`),
  ADD KEY `users_branch_id_index` (`branch_id`);
ALTER TABLE `user_visibility_settings` ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_visibility_settings_user_id_unique` (`user_id`);
ALTER TABLE `academic_calendars` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `academic_terms` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `activation_codes` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `activation_code_batches` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `admins` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `assets` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `asset_public_major` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `banners` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `blogs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `colleges` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `complaints` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `contents` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `content_device` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `countries` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `devices` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `disciplines` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `doctors` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `doctor_major` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `email_verification_tokens` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `failed_jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `majors` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `major_program` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `materials` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `material_term` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `migrations` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `notifications` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `personal_access_tokens` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `plans` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `plan_features` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `programs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `public_colleges` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `public_majors` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `student_requests` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `subscriptions` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `universities` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `university_branches` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_visibility_settings` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `academic_calendars` ADD CONSTRAINT `academic_calendars_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;
ALTER TABLE `academic_terms` ADD CONSTRAINT `academic_terms_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `academic_calendars` (`id`) ON DELETE CASCADE;
ALTER TABLE `activation_codes` ADD CONSTRAINT `activation_codes_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `activation_code_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_codes_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_codes_created_by_admin_id_foreign` FOREIGN KEY (`created_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_codes_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_codes_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `activation_codes_redeemed_by_user_id_foreign` FOREIGN KEY (`redeemed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_codes_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `activation_code_batches` ADD CONSTRAINT `activation_code_batches_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_code_batches_created_by_admin_id_foreign` FOREIGN KEY (`created_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_code_batches_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activation_code_batches_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `activation_code_batches_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `assets` ADD CONSTRAINT `assets_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assets_published_by_admin_id_foreign` FOREIGN KEY (`published_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
ALTER TABLE `asset_public_major` ADD CONSTRAINT `fk_apm_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_apm_public_major` FOREIGN KEY (`public_major_id`) REFERENCES `public_majors` (`id`);
ALTER TABLE `blogs` ADD CONSTRAINT `blogs_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blogs_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `colleges` ADD CONSTRAINT `colleges_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `university_branches` (`id`) ON DELETE CASCADE;
ALTER TABLE `complaints` ADD CONSTRAINT `complaints_assigned_admin_id_foreign` FOREIGN KEY (`assigned_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `contents` ADD CONSTRAINT `contents_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contents_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contents_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contents_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contents_published_by_admin_id_foreign` FOREIGN KEY (`published_by_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contents_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`);
ALTER TABLE `content_device` ADD CONSTRAINT `content_device_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `content_device_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;
ALTER TABLE `devices` ADD CONSTRAINT `devices_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;
ALTER TABLE `doctors` ADD CONSTRAINT `doctors_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `university_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `doctor_major` ADD CONSTRAINT `doctor_major_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_major_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE;
ALTER TABLE `majors` ADD CONSTRAINT `majors_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE CASCADE;
ALTER TABLE `major_program` ADD CONSTRAINT `major_program_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `major_program_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;
ALTER TABLE `materials` ADD CONSTRAINT `materials_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `materials_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `materials_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `material_term` ADD CONSTRAINT `material_term_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_term_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE;
ALTER TABLE `notifications` ADD CONSTRAINT `notifications_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `plan_features` ADD CONSTRAINT `plan_features_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;
ALTER TABLE `programs` ADD CONSTRAINT `programs_discipline_id_foreign` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON DELETE CASCADE;
ALTER TABLE `public_majors` ADD CONSTRAINT `fk_public_majors_college` FOREIGN KEY (`public_college_id`) REFERENCES `public_colleges` (`id`);
ALTER TABLE `sessions` ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
ALTER TABLE `student_requests` ADD CONSTRAINT `student_requests_assigned_admin_id_foreign` FOREIGN KEY (`assigned_admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_requests_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_requests_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `subscriptions` ADD CONSTRAINT `subscriptions_activation_code_id_foreign` FOREIGN KEY (`activation_code_id`) REFERENCES `activation_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `universities` ADD CONSTRAINT `universities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL;
ALTER TABLE `university_branches` ADD CONSTRAINT `fk_branch_university` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;
ALTER TABLE `users` ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `university_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_college_id_foreign` FOREIGN KEY (`college_id`) REFERENCES `colleges` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `users_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE SET NULL;
ALTER TABLE `user_visibility_settings` ADD CONSTRAINT `user_visibility_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);
    }

    public function down(): void {
        // NOTE: Rollback of individual FKs/Indexes is not auto-generated here.
        // For development resets, prefer: php artisan migrate:fresh
    }
};
