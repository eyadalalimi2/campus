<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $material_id
 * @property int|null $device_id
 * @property int|null $doctor_id
 * @property string $category
 * @property string $title
 * @property string|null $description
 * @property string|null $video_url
 * @property string|null $file_path
 * @property string|null $external_url
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Device|null $device
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read mixed $file_url
 * @property-read \App\Models\Material|null $material
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereExternalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereVideoUrl($value)
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $body
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $university_id
 * @property int|null $doctor_id
 * @property string|null $cover_image_path
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read \App\Models\University|null $university
 * @method static \Illuminate\Database\Eloquent\Builder|Blog archived()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog published()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCoverImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 */
	class Blog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $university_id
 * @property string $name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Major> $majors
 * @property-read int|null $majors_count
 * @property-read \App\Models\University $university
 * @method static \Illuminate\Database\Eloquent\Builder|College newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|College newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|College query()
 * @method static \Illuminate\Database\Eloquent\Builder|College whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|College whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|College whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|College whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|College whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|College whereUpdatedAt($value)
 */
	class College extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property string|null $source_url
 * @property string|null $file_path
 * @property string $scope
 * @property int|null $university_id
 * @property int|null $college_id
 * @property int|null $major_id
 * @property int|null $material_id
 * @property int|null $doctor_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\College|null $college
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \App\Models\Doctor|null $doctor
 * @property-read mixed $file_url
 * @property-read \App\Models\Major|null $major
 * @property-read \App\Models\Material|null $material
 * @property-read \App\Models\University|null $university
 * @method static \Illuminate\Database\Eloquent\Builder|Content forUniversity($universityId)
 * @method static \Illuminate\Database\Eloquent\Builder|Content global()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCollegeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereSourceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 */
	class Content extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $material_id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \App\Models\Material $material
 * @method static \Illuminate\Database\Eloquent\Builder|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUpdatedAt($value)
 */
	class Device extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $password
 * @property string $type
 * @property int|null $university_id
 * @property int|null $college_id
 * @property int|null $major_id
 * @property string|null $degree
 * @property int|null $degree_year
 * @property string|null $phone
 * @property string|null $photo_path
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\College|null $college
 * @property-read mixed $photo_url
 * @property-read \App\Models\Major|null $major
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Major> $majors
 * @property-read int|null $majors_count
 * @property-read \App\Models\University|null $university
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereCollegeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereDegreeYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Doctor whereUpdatedAt($value)
 */
	class Doctor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $college_id
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\College $college
 * @method static \Illuminate\Database\Eloquent\Builder|Major newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Major newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Major query()
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereCollegeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Major whereUpdatedAt($value)
 */
	class Major extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $scope
 * @property int|null $university_id
 * @property int|null $college_id
 * @property int|null $major_id
 * @property int|null $level
 * @property string|null $term
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @property-read \App\Models\College|null $college
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Device> $devices
 * @property-read int|null $devices_count
 * @property-read \App\Models\Major|null $major
 * @property-read \App\Models\University|null $university
 * @method static \Illuminate\Database\Eloquent\Builder|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCollegeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUpdatedAt($value)
 */
	class Material extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $plan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property bool $auto_renew
 * @property int|null $price_cents
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereAutoRenew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePriceCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Content> $contents
 * @property-read int|null $contents_count
 * @property-read \App\Models\Material|null $material
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 */
	class Task extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $phone
 * @property string|null $logo_path
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string $theme_mode
 * @property bool $is_active
 * @property int $use_default_theme
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\College> $colleges
 * @property-read int|null $colleges_count
 * @property-read string|null $logo_url
 * @property-read array $theme
 * @method static \Illuminate\Database\Eloquent\Builder|University newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|University newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|University query()
 * @method static \Illuminate\Database\Eloquent\Builder|University whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereThemeMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|University whereUseDefaultTheme($value)
 */
	class University extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $student_number
 * @property string|null $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $country
 * @property string|null $profile_photo_path
 * @property int|null $university_id
 * @property int|null $college_id
 * @property int|null $major_id
 * @property int|null $level
 * @property string|null $gender
 * @property string $status
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\College|null $college
 * @property-read string|null $profile_photo_url
 * @property-read \App\Models\Major|null $major
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\University|null $university
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCollegeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMajorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStudentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

