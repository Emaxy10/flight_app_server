<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
       return $this->belongsToMany(Role::class, 'user_role');
    }

    //Assign user role
    // Accepts a role ID, role name, or Role model instance

    public function assignRole($role){

        if(is_string($role)){
            $role = Role::where('role', $role)->firstorfail();
           
        }elseif(is_numeric($role)){
            $role = Role::where('role', $role)->findorfail();
        }

        return $this->roles()->attach($role);
    }

      //Check if user have role

      public function hasRole($role){
        if(is_string($role)){
            return $this->roles->contains('role', $role);
        }
        
        if(is_numeric($role)){
            return $this->roles->contains('role', $role);
        }
    }

    public function getAccessAttribute(){
        //get role
        $access = [];

        if(count($this->roles) > 0){
            foreach($this->roles as $role){
                // echo $role->permissions;
                $rolesData = json_decode($role->permissions, true); // Convert JSON string to array
                
                foreach($rolesData as $key => $value){
                    if($value == true){
                        $access[] = $key;
                    }
                }
              
            }
        }

        return $access;
    }

    public function roleHasAccess($permission){
        return in_array($permission, $this->access);
    }
}
