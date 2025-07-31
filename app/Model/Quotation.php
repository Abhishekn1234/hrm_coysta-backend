<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'customer_name',
        'project_name',
        'date',
        'items',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'include_setup',
        'express_shipping',
        'front_pages',
        'back_pages',
        'attachments',
        'customer_id',
        'status',
        'pdf_path',
        'setup_date',
        'packup_date',
        'items_pdf_path',
        'parent_quotation_id',
        'is_resubmission'
    ];

    protected $casts = [
        'items' => 'array',
        'front_page' => 'array',
        'back_page' => 'array',
        'attachments' => 'array',
        'date' => 'date',
        'include_setup' => 'boolean',
        'express_shipping' => 'boolean',
        'setup_date' => 'date',
        'packup_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            $quotation->quotation_number = 'QTN-' . strtoupper(uniqid());
        });
    }

    // In Quotation model
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'customerId'); // Use customerId as FK
    }
    public function parentQuotation()
    {
        return $this->belongsTo(Quotation::class, 'parent_quotation_id');
    }
   public function allParents()
    {
        $parents = collect();
        $current = $this->parentQuotation; // uses `parentQuotation()` relationship

        while ($current) {
            $parents->push($current);
            $current = $current->parentQuotation; // keep walking up
        }

        return $parents;
    }

}