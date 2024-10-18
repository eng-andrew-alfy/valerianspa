<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;

    //use Spatie\Permission\Contracts\Permission;
    use Spatie\Permission\Traits\HasRoles;

    use Spatie\Permission\Models\Permission;

    class Admin extends Authenticatable
    {
        use HasRoles;
        use HasFactory, Notifiable;

        protected $guard = 'admin';

        protected $fillable = ['name', 'email', 'password', 'roles_name', 'phone', 'status'];
        protected $hidden = ['password', 'remember_token'];

        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
                'roles_name' => 'array',
            ];
        }

        public function places()
        {
            return $this->hasMany(Place::class, 'created_by');
        }

        public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
        {
            return $this->belongsToMany(Permission::class);
        }

        public function hasPermission($permission)
        {
            return $this->permissions()->where('name', $permission)->exists();
        }

        public function canAny($abilities, $arguments = [])
        {
            foreach ($abilities as $ability) {
                if ($this->can($ability, $arguments)) {
                    return true;
                }
            }

            return false;
        }


        public function canAll(array $permissions, $arguments = [])
        {
            foreach ($permissions as $e) {
                if (!$this->can($e, $arguments)) return false;
            }

            return true;
        }

        public function can($ability, $arguments = [])
        {
            return $this->permissions->contains('name', $ability);
        }


    }
