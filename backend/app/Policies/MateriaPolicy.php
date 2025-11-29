<?php

namespace App\Policies;

use App\Models\Materia;
use App\Models\User;

class MateriaPolicy
{
     /**
      * Determine whether the user can view any models.
      */
     public function viewAny(User $user): bool
     {
         return true;
     }

     /**
      * Determine whether the user can view the model.
      */
     public function view(User $user, Materia $materia): bool
     {
         return true;
     }
}
