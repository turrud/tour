<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Image;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the image can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list images');
    }

    /**
     * Determine whether the image can view the model.
     */
    public function view(User $user, Image $model): bool
    {
        return $user->hasPermissionTo('view images');
    }

    /**
     * Determine whether the image can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create images');
    }

    /**
     * Determine whether the image can update the model.
     */
    public function update(User $user, Image $model): bool
    {
        return $user->hasPermissionTo('update images');
    }

    /**
     * Determine whether the image can delete the model.
     */
    public function delete(User $user, Image $model): bool
    {
        return $user->hasPermissionTo('delete images');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete images');
    }

    /**
     * Determine whether the image can restore the model.
     */
    public function restore(User $user, Image $model): bool
    {
        return false;
    }

    /**
     * Determine whether the image can permanently delete the model.
     */
    public function forceDelete(User $user, Image $model): bool
    {
        return false;
    }
}
