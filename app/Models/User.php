<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project')
            ->withPivot('role');
    }

    public function avatar()
    {
        $first = trim($this->firstname ?? '');
        $last = trim($this->lastname ?? '');

        $initials = strtoupper(
            mb_substr($first, 0, 1) .
            mb_substr($last, 0, 1)
        );

        $colors = [
            'bg-red-500', 'bg-pink-500', 'bg-purple-500', 'bg-indigo-500',
            'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-orange-500',
            'bg-teal-500', 'bg-emerald-500', 'bg-cyan-500'
        ];

        $index = crc32($this->email ?? $this->name ?? 'user') % count($colors);
        $color = $colors[$index];

        return [
            'initials' => $initials,
            'color' => $color
        ];
    }

}
