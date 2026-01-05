<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//created by mohammed 05/01
class Resource extends Model
{
   
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'specifications',
        'resource_status',
        'location',
        'responsable_id',
        'is_active',
    ];

    //conversion auto of the data types
    protected function casts(): array
    {
        return [
            'specifications' => 'array',  
            'is_active' => 'boolean',
        ];
    }

    public function category(){
        return $this->belongsTo(ResourceCategory::class, 'category_id');  
    }

    
    public function responsable(){
        return $this->belongsTo(User::class, 'responsable_id');  //the responsable who managed the resource
    }


    public function reservations(){
        return $this->hasMany(Reservation::class);  // one resource can be reserved many times 
    }


    public function maintenances(){
        return $this->hasMany(Maintenance::class);
    }


    public function incidents(){
        return $this->hasMany(Incident::class);  // one resource can be associated with mltpl incidents 
    }


}
