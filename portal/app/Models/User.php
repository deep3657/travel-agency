<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Application user (LLD §4.1).
 *
 * Two distinct populations share this table, separated by `user_type`:
 *   - `staff`     — admin / agent users (Spatie roles).
 *   - `customer`  — self-service portal users tied to a customers row.
 *
 * Auth guards in config/auth.php scope each guard to one user_type so that
 * staff and customer credentials cannot collide.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    public const TYPE_STAFF = 'staff';

    public const TYPE_CUSTOMER = 'customer';

    /** @var string */
    protected $guard_name = 'web';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'customer_id',
        'is_active',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isStaff(): bool
    {
        return $this->user_type === self::TYPE_STAFF;
    }

    public function isCustomer(): bool
    {
        return $this->user_type === self::TYPE_CUSTOMER;
    }

    public function isAdmin(): bool
    {
        return $this->isStaff() && $this->hasRole('admin');
    }

    public function isAgent(): bool
    {
        return $this->isStaff() && $this->hasRole('agent');
    }
}
