<?php

    namespace App\Models;

    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Spatie\Permission\Traits\HasRoles;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    class User extends Authenticatable implements JWTSubject
    {
        use HasRoles;

        use HasFactory, Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name',
            'phone',
            'unique_code',
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array<int, string>
         */
        protected $hidden = [
            'remember_token',
        ];

        /**
         * The attributes that should be cast to native types.
         *
         * @var array<string, string>
         */
        protected $casts = [
            'phone_verified_at' => 'datetime',
        ];

        public function otpCodes()
        {
            return $this->hasMany(OtpCode::class);
        }

//        protected function casts(): array
////        {
////            return [
////                'email_verified_at' => 'datetime',
////                'password' => 'hashed',
////            ];
////        }

        public static function generateUniqueCode()
        {
            do {
                $code = rand('999999', '1111'); // Generate a random 6-digit number
            } while (self::where('code', $code)->exists());

            return $code;
        }

        public function orders()
        {
            return $this->hasMany(Order::class);
        }

        public function getJWTIdentifier()
        {
            return $this->getKey();
        }

        /**
         * Get the custom claims to be added to the JWT.
         *
         * @return array
         */
        public function getJWTCustomClaims()
        {
            return [];
        }
    }

