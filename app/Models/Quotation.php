<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    // Explicitly define table name if different from 'quotations'
    protected $table = 'quotation'; // Add this line if your table is named differently

    protected $fillable = [
        'quotation_number',
        'customer_name',
        'project_name',
        'quotation_date',
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
        'pdfPath',
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
        'date' => 'datetime',
        'include_setup' => 'boolean',
        'express_shipping' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            $quotation->quotation_number = 'QTN-' . strtoupper(uniqid());
        });
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'customer_id');
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