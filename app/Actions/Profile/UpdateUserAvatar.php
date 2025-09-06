<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserAvatar
{
    protected string $disk = 'public';
    protected string $dir  = 'avatars';

    public function execute(User $user, UploadedFile $file): User
    {
        // حذف القديم إن وُجد
        if ($user->avatar_path) {
            Storage::disk($this->disk)->delete($user->avatar_path);
        }

        $path = $file->store($this->dir, $this->disk);

        $user->avatar_path = $path;
        $user->save();

        return $user->fresh();
    }

    public function delete(User $user): User
    {
        if ($user->avatar_path) {
            Storage::disk($this->disk)->delete($user->avatar_path);
            $user->avatar_path = null;
            $user->save();
        }
        return $user->fresh();
    }
}
