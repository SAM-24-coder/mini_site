<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Payment;

class Userdb extends Model
{
    protected $table = 'usersdb'; // Spécifie le nom exact de la table
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'name',
        'surname', 
        'email',
        'gender',
        'date_of_birth',
        'phone',
        'password',
        'status',
    ];
    // Méthode pour obtenir le statut formaté
    public function getStatusLabelAttribute()
    {
        return $this->status === 'active' ? 'Actif' : 'Bloqué';
    }
    
    // Méthode pour obtenir la classe CSS du statut
    public function getStatusClassAttribute()
    {
        return $this->status === 'active' 
        ? 'bg-green-100 text-green-800' 
        : 'bg-red-100 text-red-800';
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'registration_timestamp' => 'datetime',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    
}