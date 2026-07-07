<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'address',
        'group_id'
    ];
    
    protected function casts():array{
        return [
            'name'=>'string',
            'email'=>'string',
            'phone'=>'string',
            'address'=>'string',
            'notes'=>'string',
        ];
    }

    public function scopeSearch(Builder $query, ?string $term){
        if(empty($term)){
            return $query;
        }
        return $query->where(function(Builder $subQuery) use ($term){
            $subQuery->whereLike('name',$term)->orWhereLike('email',$term)->orWhereLike('phone',$term);
        });
    }

    public function group(){
        return $this->belongsTo(Group::class,"group_id");
    }
}
