<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'esi_id', 'esi_status', 'esi_coverage', 'esi_type', 'esi_sum_assured', 'esi_provider', 'esi_policy_no', 'esi_expires',
        'pf_number', 'pf_balance', 'pf_employer_contribution', 'pf_employee_contribution',
        'loan_type', 'loan_amount_issued', 'loan_outstanding', 'loan_repayment', 'loan_next_due',
        'gratuity_accrued', 'gratuity_vesting_period', 'gratuity_last_accrual', 'gratuity_projected',
        'bonus_annual', 'bonus_status', 'bonus_festival', 'bonus_projected',
        'incentive_q1', 'incentive_q2', 'incentive_q3', 'incentive_q4_projected',
        'visa_type', 'visa_country', 'visa_expires', 'visa_status',
        'travel_entitlement', 'travel_used', 'travel_last_used', 'travel_next_eligible',
        'feedback_last_review', 'feedback_rating', 'feedback_comments',
        'other_health_insurance', 'other_gym_membership', 'other_meal_allowance',
        'other_education_assistance', 'other_mobile_allowance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
