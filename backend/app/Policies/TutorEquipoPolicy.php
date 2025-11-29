<?php

namespace App\Policies;

use App\Models\TutorEquipo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TutorEquipoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $allowedRoles = ['administrador', 'alumno', 'profesor'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TutorEquipo $tutorEquipo): bool
    {
        $allowedRoles = ['administrador', 'alumno', 'profesor'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $allowedRoles = ['administrador'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TutorEquipo $tutorEquipo = null): bool
    {
        $allowedRoles = ['administrador'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TutorEquipo $tutorEquipo): bool
    {
        $allowedRoles = ['administrador'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TutorEquipo $tutorEquipo): bool
    {
        $allowedRoles = ['administrador'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TutorEquipo $tutorEquipo): bool
    {
        $allowedRoles = ['administrador'];
        return !empty(array_intersect($user->roles ?? [], $allowedRoles));
    }
}