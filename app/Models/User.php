<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
      public function employmentHistories()
    {
        return $this->hasMany(EmploymentHistory::class, 'user_id');
    }
    public function departmentInfo()
{
    return $this->belongsTo(Department::class, 'department');
}
public function documents()
{
    return $this->hasMany(EmployeeDocument::class, 'user_id');
}
// app/Models/User.php
public function leaveRequests()
{
    return $this->hasMany(LeaveRequest::class, 'employee_id');
}
public function attendances()
{
    return $this->hasMany(\App\Models\Attendance::class, 'user_id');
}


// public function documents()
// {
//     return $this->hasMany(Document::class, 'user_id');
// }

    /**
     * 
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
 protected $fillable = [
    // Basic Info
    'first_name', 'last_name', 'name', 'user_type', 'email', 'email_verified_at', 'password', 'phone',
    'place', 'address', 'gender', 'salutation', 'organization',

    // Profile & Employment
    'designation', 'role', 'position', 'department', 'hire_date', 'reporting_manager',
    'status', 'join_type', 'join_date', 'work_location', 'employment_type',
    'staff_type', 'nature_of_staff', 'suspend', 'isLogin',

    // Contact
    'work_phone', 'personal_email', 'parent_mobile',
    'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone',
    'emergency_contact_email', 'emergency_contact_1', 'emergency_contact_2',

    // Salary & Allowances
    'hourly_rate', 'monthly_rate', 'annual_ctc', 'basic_salary', 'base_salary',
    'hra', 'special_allowances', 'housing_allowance', 'transport_allowance',
    'medical_allowance', 'other_allowances', 'daily_remuneration',
    'pay_frequency', 'rent_allowance_percent', 'ptax', 'tds', 'loan',

    // Bank Details
    'bank_name', 'account_holder_name', 'account_number', 'ifsc_code',
    'branch', 'routing_number', 'payment_method',

    // Attendance & Personal
    'attendance', 'date_of_birth', 'blood_group', 'covid_vaccinated', 'casual_leaves',

    // Qualification & Skills
    'qualification', 'experience', 'expertise', 'qualifications', 'experiences', 'skills',

    // Documents
    'resume', 'id_proof', 'employment_contract', 'medical_certificate', 'education_certificates',
    'aadhar_front', 'aadhar_back', 'driving_license_front', 'driving_license_back',
    'photo', 'passport_size_photo', 'pan_card',
    'passport_front', 'passport_back', 'pf_document', 'esi_document',

    // Additional Document Info
    'info_date', 'info_title', 'info_description',
    'bank_document_date', 'bank_document_title', 'bank_document_description', 'bank_document_file',

    // Statutory Numbers
    'pf_no', 'esi_card_no','created_at','updated_at',

    // Auth & Profile
    'ProfileImage', 'image', 'remember_token', 'verified_at'
];

public function tasks()
{
    return $this->hasMany(Task::class, 'user_id');
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
           public function departmentRelation()
{
    return $this->belongsTo(\App\Models\Department::class, 'department', 'id');
}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}