<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'backend_users';

    protected $with = ['roles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        // 'roles'
    ];

    protected $dates = ['deleted_at'];



    public function getUserPermisionByNameArray()
    {
        $permissions = Permission::select('name')->get();
        // dd($permissions);
        $allPermissions = [];
        foreach ($permissions as $permission) {
            $allPermissions[$permission->name] = $this->can($permission);
        }

        return $allPermissions;
    }

    public function registerMediaConversions(Media $media = null): void
    {


        $this
            ->addMediaConversion('preview')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default');
        $this
            ->addMediaCollection('site icons');
        $this
            ->addMediaCollection('avatar');
        // $this
        //     ->addMediaCollection('Banners');
        // $this
        //     ->addMediaCollection('Services');
        // $this
        //     ->addMediaCollection('Service Categories');
        // $this
        //     ->addMediaCollection('Service Categories Banners');
        // $this
        //     ->addMediaCollection('Service Types');
        // $this
        //     ->addMediaCollection('Service Types');
        // $this
        //     ->addMediaCollection('Service Types Banners');
    }

    // public function branch() {
    //     return $this->belongsTo(Branch::class ,'branch', 'id');
    // }

    // public function serviceTypes() {
    //     return $this->hasMany(Branch::class);
    // }

    public function transactions() {
        return $this->hasManyThrough(Transaction::class, Booking::class, 'id', 'booking_id');
    }

    // public function getRolesAttribute() {
    //     return $this->getRoleNames();
    //     // return $image;
    // }
}
