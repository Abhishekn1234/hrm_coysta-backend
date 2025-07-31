<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GstDetail extends Model
{
    use HasFactory;
     protected $table = 'gst_details';
    protected $fillable = [
        'customer_id',
        'gst_number',
        'registered_address',
        'place_of_supply',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
