<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Contact extends Model
{
    use HasFactory;
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

    #[Scope]
    protected function scopeSearch(Builder $query, ?string $term):void {
        if(empty($term)){
            return;
        }
        $query->where(function(Builder $subQuery) use($term){
             $subQuery->where('name','like',"%{$term}%")->orWhere('email','like',"%{$term}%")->orWhereHas('group',function($relationQuery) use($term){
                $relationQuery->where('name','like',"%{$term}%");
             });
        });
      
    }

    public function group(){
        return $this->belongsTo(Group::class,"group_id");
    }
}
