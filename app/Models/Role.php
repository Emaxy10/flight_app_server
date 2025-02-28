<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function users(){
        
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function getAccessAttribute(){
        $access = [];

        if(count($this->permissions) > 0){
            foreach($this->permissions as $key => $value){
                if($value == true){
                    $access[] = $key;
                }
            }
        }
        return $access;
    }

    public function hasAccess($permission){
        //dd($this->access);
        return in_array($permission, $this->access);
    }
}
