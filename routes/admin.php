<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return redirect()->route('admin.auth.login');
});

Route::get('/home', function () {
    return redirect()->route('admin.auth.login');
});

Route::get('/clear_cache', function () {
    $exitCode2 = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return '<h1>Cache facade value cleared</h1>';
});


Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.auth.login');
    });

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');

        Route::get('candidate_registration', 'LoginController@candidate_registration')->name('candidate_registration');
        Route::post('candidate_registration', 'LoginController@candidate_registration_submit');

        Route::get('personality_test/{id}', 'LoginController@personality_test')->name('personality_test');
        Route::post('personality_test_submit', 'LoginController@personality_test_submit')->name('personality_test_submit');

        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('check/{id}', 'LoginController@check')->name('check');
        Route::post('verify', 'LoginController@verify')->name('verify');
        Route::post('api-login', 'LoginController@apiLogin')->middleware('web');
    });

    // Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::post('add-new', 'ProjectController@store')->name('store');
        Route::get('list', 'ProjectController@list')->name('list');
        Route::post('delete', 'ProjectController@delete')->name('delete');
        Route::post('status', 'ProjectController@status')->name('status');
        Route::get('view/{id}', 'ProjectController@view')->name('view');
        Route::get('edit/{id}', 'ProjectController@edit')->name('edit');
        Route::put('update/{id}', 'ProjectController@update')->name('update');
    });

    Route::group(['prefix' => 'job', 'as' => 'job.'], function () {
        Route::post('add-new', 'JobController@store')->name('store');
        Route::get('list', 'JobController@list')->name('list');
        Route::post('delete', 'JobController@delete')->name('delete');
        Route::get('view/{id}', 'JobController@view')->name('view');
        Route::get('edit/{id}', 'JobController@edit')->name('edit');
        Route::put('update/{id}', 'JobController@update')->name('update');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('view', 'ProfileController@view')->name('view');
        Route::get('update/{id}', 'ProfileController@edit')->name('update');
        Route::post('update/{id}', 'ProfileController@update');
        Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
        // Route::get('settings', function () {
        //     return view('admin-views.react-module');
        // })->name('settings');
    });
    Route::get('crm', function () {
            return view('admin-views.react-module');
        })->name('crm');
       
    Route::get('pos', function () {
            return view('admin-views.reactpos-module');
        })->name('pos');  
    Route::get('inventory_new', function () {
            return view('admin-views.inventory-module');
        })->name('inventory_new'); 
        
    Route::get('purchase', function () {
            return view('admin-views.purchase-module');
        })->name('purchase'); 
    Route::get('quotation', function () {
            return view('admin-views.quotation-module');
        })->name('quotation');      
     Route::get('inventory_planning', function () {
            return view('admin-views.inventory-planning-module');
        })->name('inventory_planning');  
     Route::get('products', function () {
            return view('admin-views.products-module');
        })->name('products');        

       Route::get('hrm', function () {
            return view('admin-views.react-hrm-module');
        })->name('hrm');
         Route::get('people', function () {
            return view('admin-views.people-module');
        })->name('people');
    Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::post('add-new', 'StaffController@store')->name('store');
        Route::get('list', 'StaffController@list')->name('list');
        Route::post('delete', 'StaffController@delete')->name('delete');
        Route::post('status', 'StaffController@status')->name('status');
        Route::get('view/{id}', 'StaffController@view')->name('view');
        Route::get('edit/{id}', 'StaffController@edit')->name('edit');
        Route::put('update/{id}', 'StaffController@update')->name('update');

        Route::get('add_offer_letters/{staff_id}', 'StaffController@add_offer_letters')->name('add_offer_letters');
        Route::post('add_offer_letters/{staff_id}', 'StaffController@add_offer_letters_submit');
        Route::get('edit_offer_letters/{staff_id}/{id}', 'StaffController@edit_offer_letters')->name('edit_offer_letters');
        Route::post('edit_offer_letters/{staff_id}/{id}', 'StaffController@update_offer_letters');
        Route::post('delete_offer_letters', 'StaffController@delete_offer_letters')->name('delete_offer_letters');
        Route::get('generate_offer_letter/{id}/{letter_id}', 'StaffController@generate_offer_letter')->name('generate_offer_letter');



        Route::get('add_releiving_letters/{staff_id}', 'StaffController@add_releiving_letters')->name('add_releiving_letters');
        Route::post('add_releiving_letters/{staff_id}', 'StaffController@add_releiving_letters_submit');
        Route::get('edit_releiving_letters/{staff_id}/{id}', 'StaffController@edit_releiving_letters')->name('edit_releiving_letters');
        Route::post('edit_releiving_letters/{staff_id}/{id}', 'StaffController@update_releiving_letters');
        Route::post('delete_releiving_letters', 'StaffController@delete_releiving_letters')->name('delete_releiving_letters');
        Route::get('generate_releiving_letter/{id}/{letter_id}', 'StaffController@generate_releiving_letter')->name('generate_releiving_letter');



        Route::get('add_warning_letters/{staff_id}', 'StaffController@add_warning_letters')->name('add_warning_letters');
        Route::post('add_warning_letters/{staff_id}', 'StaffController@add_warning_letters_submit');
        Route::get('edit_warning_letters/{staff_id}/{id}', 'StaffController@edit_warning_letters')->name('edit_warning_letters');
        Route::post('edit_warning_letters/{staff_id}/{id}', 'StaffController@update_warning_letters');
        Route::post('delete_warning_letters', 'StaffController@delete_warning_letters')->name('delete_warning_letters');
        Route::get('generate_warning_letter/{id}/{letter_id}', 'StaffController@generate_warning_letter')->name('generate_warning_letter');



        Route::get('add_termination_letters/{staff_id}', 'StaffController@add_termination_letters')->name('add_termination_letters');
        Route::post('add_termination_letters/{staff_id}', 'StaffController@add_termination_letters_submit');
        Route::get('edit_termination_letters/{staff_id}/{id}', 'StaffController@edit_termination_letters')->name('edit_termination_letters');
        Route::post('edit_termination_letters/{staff_id}/{id}', 'StaffController@update_termination_letters');
        Route::post('delete_termination_letters', 'StaffController@delete_termination_letters')->name('delete_termination_letters');
        Route::get('generate_termination_letter/{id}/{letter_id}', 'StaffController@generate_termination_letter')->name('generate_termination_letter');



        Route::get('add_experiences/{staff_id}', 'StaffController@add_experiences')->name('add_experiences');
        Route::post('add_experiences/{staff_id}', 'StaffController@add_experiences_submit');
        Route::get('edit_experiences/{staff_id}/{id}', 'StaffController@edit_experiences')->name('edit_experiences');
        Route::post('edit_experiences/{staff_id}/{id}', 'StaffController@update_experiences');
        Route::post('delete_experiences', 'StaffController@delete_experiences')->name('delete_experiences');
        Route::get('generate_experience_certificate/{id}/{letter_id}', 'StaffController@generate_experience_certificate')->name('generate_experience_certificate');


        Route::get('add_certificates/{staff_id}', 'StaffController@add_certificates')->name('add_certificates');
        Route::post('add_certificates/{staff_id}', 'StaffController@add_certificates_submit');
        Route::post('delete_certificates', 'StaffController@delete_certificates')->name('delete_certificates');

        Route::post('send_whatsapp', 'StaffController@send_whatsapp')->name('send_whatsapp');
        Route::post('send_email', 'StaffController@send_email')->name('send_email');
    });

    Route::group(['prefix' => 'document', 'as' => 'document.'], function () {
        Route::get('add_offer_letters', 'DocumentController@add_offer_letters')->name('add_offer_letters');
        Route::get('generate_offer_letter/{letter_id}', 'DocumentController@generate_offer_letter')->name('generate_offer_letter');

        Route::get('add_releiving_letters', 'DocumentController@add_releiving_letters')->name('add_releiving_letters');
        Route::get('generate_releiving_letter/{letter_id}', 'DocumentController@generate_releiving_letter')->name('generate_releiving_letter');

        Route::get('add_warning_letters', 'DocumentController@add_warning_letters')->name('add_warning_letters');
        Route::get('generate_warning_letter/{letter_id}', 'DocumentController@generate_warning_letter')->name('generate_warning_letter');

        Route::get('add_termination_letters', 'DocumentController@add_termination_letters')->name('add_termination_letters');
        Route::get('generate_termination_letter/{letter_id}', 'DocumentController@generate_termination_letter')->name('generate_termination_letter');

        Route::get('add_experiences', 'DocumentController@add_experiences')->name('add_experiences');
        Route::get('generate_experience_certificate/{letter_id}', 'DocumentController@generate_experience_certificate')->name('generate_experience_certificate');

        Route::get('add_certificates', 'DocumentController@add_certificates')->name('add_certificates');
    });

    Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
        Route::post('add-new', 'ClientController@store')->name('store');
        Route::get('list', 'ClientController@list')->name('list');
        Route::post('delete', 'ClientController@delete')->name('delete');
        Route::post('status', 'ClientController@status')->name('status');
        Route::get('view/{id}', 'ClientController@view')->name('view');
        Route::get('edit/{id}', 'ClientController@edit')->name('edit');
        Route::put('update/{id}', 'ClientController@update')->name('update');

        Route::post('send_whatsapp', 'ClientController@send_whatsapp')->name('send_whatsapp');
        Route::post('send_email', 'ClientController@send_email')->name('send_email');
    });

    Route::group(['prefix' => 'proposal', 'as' => 'proposal.'], function () {
        Route::post('add-new', 'ProposalController@store')->name('store');
        Route::get('list', 'ProposalController@list')->name('list');
        Route::post('delete', 'ProposalController@delete')->name('delete');
        Route::post('status', 'ProposalController@status')->name('status');
        Route::get('view/{id}', 'ProposalController@view')->name('view');
        Route::get('edit/{id}', 'ProposalController@edit')->name('edit');
        Route::put('update/{id}', 'ProposalController@update')->name('update');

        Route::get('generate_proposal/{id}', 'ProposalController@generate_proposal')->name('generate_proposal');
    });

    Route::group(['prefix' => 'quotation', 'as' => 'quotation.'], function () {
        Route::post('add-new', 'QuotationController@store')->name('store');
        Route::get('list', 'QuotationController@list')->name('list');
        Route::post('delete', 'QuotationController@delete')->name('delete');
        Route::post('status', 'QuotationController@status')->name('status');
        Route::get('view/{id}', 'QuotationController@view')->name('view');
        Route::get('edit/{id}', 'QuotationController@edit')->name('edit');
        Route::put('update/{id}', 'QuotationController@update')->name('update');

        Route::post('quotation_status_change', 'QuotationController@quotation_status_change')->name('quotation_status_change');
        Route::get('get_proposal', 'QuotationController@get_proposal')->name('get_proposal');

        Route::get('generate_quotation/{id}', 'QuotationController@generate_quotation')->name('generate_quotation');

        Route::get('add_items/{quotation_id}', 'QuotationController@add_items')->name('add_items');
        Route::post('add_items/{quotation_id}', 'QuotationController@add_items_submit');
        Route::post('delete_items', 'QuotationController@delete_items')->name('delete_items');

        Route::get('edit_items/{quotation_id}/{id}', 'QuotationController@edit_items')->name('edit_items');
        Route::post('edit_items/{quotation_id}/{id}', 'QuotationController@update_items');
    });

    Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
        Route::post('add-new', 'InvoiceController@store')->name('store');
        Route::get('list', 'InvoiceController@list')->name('list');
        Route::post('delete', 'InvoiceController@delete')->name('delete');
        Route::post('status', 'InvoiceController@status')->name('status');
        Route::get('view/{id}', 'InvoiceController@view')->name('view');
        Route::get('edit/{id}', 'InvoiceController@edit')->name('edit');
        Route::put('update/{id}', 'InvoiceController@update')->name('update');

        Route::post('invoice_status_change', 'InvoiceController@invoice_status_change')->name('invoice_status_change');
        Route::get('get_quotation', 'InvoiceController@get_quotation')->name('get_quotation');

        Route::get('generate_invoice/{id}', 'InvoiceController@generate_invoice')->name('generate_invoice');

        Route::get('add_receipts/{invoice_id}', 'InvoiceController@add_receipts')->name('add_receipts');
        Route::post('add_receipts/{invoice_id}', 'InvoiceController@add_receipts_submit');
        Route::post('delete_receipts', 'InvoiceController@delete_receipts')->name('delete_receipts');

        Route::get('edit_receipts/{invoice_id}/{id}', 'InvoiceController@edit_receipts')->name('edit_receipts');
        Route::post('edit_receipts/{invoice_id}/{id}', 'InvoiceController@update_receipts');

        Route::get('generate_receipt/{id}', 'InvoiceController@generate_receipt')->name('generate_receipt');
    });

    Route::group(['prefix' => 'hire', 'as' => 'hire.'], function () {
        Route::get('list', 'HireController@list')->name('list');
        Route::post('hire_now', 'HireController@hire_now')->name('hire_now');
    });

    Route::group(['prefix' => 'hire_request', 'as' => 'hire_request.'], function () {
        Route::get('list', 'Hire_requestController@list')->name('list');
        Route::get('view/{id}', 'Hire_requestController@view')->name('view');
    });

    Route::group(['prefix' => 'interview', 'as' => 'interview.'], function () {
        Route::get('list', 'InterviewController@list')->name('list');
        Route::get('view/{id}', 'InterviewController@view')->name('view');

        Route::get('add_marks/{interview_id}', 'InterviewController@add_marks')->name('add_marks');
        Route::post('add_marks/{interview_id}', 'InterviewController@add_marks_submit');
        Route::post('delete_marks', 'InterviewController@delete_marks')->name('delete_marks');

        Route::get('edit_marks/{interview_id}/{id}', 'InterviewController@edit_marks')->name('edit_marks');
        Route::post('edit_marks/{interview_id}/{id}', 'InterviewController@update_marks');
    });

    Route::group(['prefix' => 'certificate', 'as' => 'certificate.'], function () {
        Route::post('add-new', 'CertificateController@store')->name('store');
        Route::get('list', 'CertificateController@list')->name('list');
        Route::post('delete', 'CertificateController@delete')->name('delete');
        Route::get('view/{id}', 'CertificateController@view')->name('view');
        Route::get('edit/{id}', 'CertificateController@edit')->name('edit');
        Route::put('update/{id}', 'CertificateController@update')->name('update');
    });

    Route::group(['prefix' => 'task', 'as' => 'task.'], function () {
        Route::post('add-new', 'TaskController@store')->name('store');
        Route::get('list', 'TaskController@list')->name('list');
        Route::post('delete', 'TaskController@delete')->name('delete');
        Route::post('status', 'TaskController@status')->name('status');
        Route::post('ceo_approval', 'TaskController@ceo_approval')->name('ceo_approval');
        Route::get('view/{id}', 'TaskController@view')->name('view');
        Route::get('edit/{id}', 'TaskController@edit')->name('edit');
        Route::put('update/{id}', 'TaskController@update')->name('update');

        Route::post('task_start', 'TaskController@task_start')->name('task_start');
        Route::post('task_pause', 'TaskController@task_pause')->name('task_pause');
        Route::post('task_resume', 'TaskController@task_resume')->name('task_resume');
        Route::post('task_end', 'TaskController@task_end')->name('task_end');
    });

    Route::group(['prefix' => 'backlog', 'as' => 'backlog.'], function () {
        Route::post('add-new', 'BacklogController@store')->name('store');
        Route::get('list', 'BacklogController@list')->name('list');
        Route::post('delete', 'BacklogController@delete')->name('delete');
        Route::post('add_to_task', 'BacklogController@add_to_task')->name('add_to_task');
        Route::post('ceo_approval', 'BacklogController@ceo_approval')->name('ceo_approval');
        Route::get('get_sprint', 'BacklogController@get_sprint')->name('get_sprint');
        Route::get('view/{id}', 'BacklogController@view')->name('view');
        Route::get('edit/{id}', 'BacklogController@edit')->name('edit');
        Route::put('update/{id}', 'BacklogController@update')->name('update');

        Route::get('bulk-import', 'BacklogController@bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'BacklogController@bulk_import_data');

        Route::post('time_edit', 'BacklogController@time_edit')->name('time_edit');
    });

    Route::group(['prefix' => 'lead', 'as' => 'lead.'], function () {
        Route::post('add-new', 'LeadController@store')->name('store');
        Route::get('list', 'LeadController@list')->name('list');
        Route::post('delete', 'LeadController@delete')->name('delete');
        Route::post('ceo_approval', 'LeadController@ceo_approval')->name('ceo_approval');
        Route::get('view/{id}', 'LeadController@view')->name('view');
        Route::get('edit/{id}', 'LeadController@edit')->name('edit');
        Route::put('update/{id}', 'LeadController@update')->name('update');

        Route::get('bulk-import', 'LeadController@bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'LeadController@bulk_import_data');

        Route::post('lead_status_change', 'LeadController@lead_status_change')->name('lead_status_change');
        Route::post('processAction', 'LeadController@processAction')->name('processAction');

        Route::post('send_whatsapp', 'LeadController@send_whatsapp')->name('send_whatsapp');
        Route::post('send_email', 'LeadController@send_email')->name('send_email');
    });

    Route::group(['prefix' => 'candidate', 'as' => 'candidate.'], function () {
        Route::post('add-new', 'CandidateController@store')->name('store');
        Route::get('list', 'CandidateController@list')->name('list');
        Route::post('delete', 'CandidateController@delete')->name('delete');
        Route::get('view/{id}', 'CandidateController@view')->name('view');
        Route::get('edit/{id}', 'CandidateController@edit')->name('edit');
        Route::put('update/{id}', 'CandidateController@update')->name('update');

        Route::get('bulk-import', 'CandidateController@bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'CandidateController@bulk_import_data');

        Route::get('add_interview/{candidate_id}', 'CandidateController@add_interview')->name('add_interview');
        Route::post('add_interview/{candidate_id}', 'CandidateController@add_interview_submit');
        Route::post('delete_interview', 'CandidateController@delete_interview')->name('delete_interview');

        Route::get('staff_convert/{candidate_id}', 'CandidateController@staff_convert')->name('staff_convert');
        Route::post('staff_convert/{candidate_id}', 'CandidateController@staff_convert_submit');

        Route::post('interview_status_change', 'CandidateController@interview_status_change')->name('interview_status_change');

        Route::post('processAction', 'CandidateController@processAction')->name('processAction');
        Route::post('send_email', 'CandidateController@send_email')->name('send_email');
    });

    Route::group(['prefix' => 'quotation_report', 'as' => 'quotation_report.'], function () {
        Route::get('list', 'Quotation_reportController@list')->name('list');
    });

    Route::group(['prefix' => 'invoice_report', 'as' => 'invoice_report.'], function () {
        Route::get('list', 'Invoice_reportController@list')->name('list');
        Route::get('add_receipts/{invoice_report_id}', 'Invoice_reportController@add_receipts')->name('add_receipts');
    });

    Route::group(['prefix' => 'post_usage_report', 'as' => 'post_usage_report.'], function () {
        Route::get('list', 'Post_usage_reportController@list')->name('list');
    });

    Route::group(['prefix' => 'punching_report', 'as' => 'punching_report.'], function () {
        Route::get('list', 'Punching_reportController@list')->name('list');
        Route::get('export_attendence', 'Punching_reportController@export_attendence')->name('export_attendence');
    });

    Route::group(['prefix' => 'salary_report', 'as' => 'salary_report.'], function () {
        Route::get('list', 'Salary_reportController@list')->name('list');
        Route::get('export_attendence', 'Salary_reportController@export_attendence')->name('export_attendence');

        Route::get('export_salary', 'Salary_reportController@export_salary')->name('export_salary');
    });

    Route::group(['prefix' => 'point_setting', 'as' => 'point_setting.'], function () {
        Route::post('add-new', 'Point_settingController@store')->name('store');
        Route::get('list', 'Point_settingController@list')->name('list');
        Route::get('view/{id}', 'Point_settingController@view')->name('view');
        Route::get('edit/{id}', 'Point_settingController@edit')->name('edit');
        Route::put('update/{id}', 'Point_settingController@update')->name('update');
    });

    Route::group(['prefix' => 'leader_board', 'as' => 'leader_board.'], function () {
        Route::get('list', 'Leader_boardController@list')->name('list');
    });

    Route::group(['prefix' => 'punching_report', 'as' => 'punching_report.'], function () {
        Route::get('list', 'Punching_reportController@list')->name('list');
    });

    Route::group(['prefix' => 'stafftask', 'as' => 'stafftask.'], function () {
        Route::get('list', 'StafftaskController@list')->name('list');
        Route::post('ceo_approval', 'StafftaskController@ceo_approval')->name('ceo_approval');
        Route::get('view/{id}', 'StafftaskController@view')->name('view');
        Route::get('edit/{id}', 'StafftaskController@edit')->name('edit');
        Route::put('update/{id}', 'StafftaskController@update')->name('update');

        Route::post('task_restart', 'StafftaskController@task_restart')->name('task_restart');
        Route::post('track_time_edit', 'StafftaskController@track_time_edit')->name('track_time_edit');
    });

    Route::group(['prefix' => 'testcase', 'as' => 'testcase.'], function () {
        Route::get('list', 'TestcaseController@list')->name('list');
        Route::get('view/{id}', 'TestcaseController@view')->name('view');
        Route::get('edit/{id}', 'TestcaseController@edit')->name('edit');
        Route::put('update/{id}', 'TestcaseController@update')->name('update');
    });

    Route::group(['prefix' => 'backlog', 'as' => 'backlog.'], function () {
        Route::post('add-new', 'BacklogController@store')->name('store');
        Route::get('list', 'BacklogController@list')->name('list');
        Route::post('delete', 'BacklogController@delete')->name('delete');
        Route::post('status', 'BacklogController@status')->name('status');
        Route::get('view/{id}', 'BacklogController@view')->name('view');
        Route::get('edit/{id}', 'BacklogController@edit')->name('edit');
        Route::put('update/{id}', 'BacklogController@update')->name('update');
    });

    Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function () {
        Route::post('add-new', 'InventoryController@store')->name('store');
        Route::get('list', 'InventoryController@list')->name('list');
        Route::post('delete', 'InventoryController@delete')->name('delete');
        Route::get('view/{id}', 'InventoryController@view')->name('view');
        Route::get('edit/{id}', 'InventoryController@edit')->name('edit');
        Route::put('update/{id}', 'InventoryController@update')->name('update');
    });
    // });
});
