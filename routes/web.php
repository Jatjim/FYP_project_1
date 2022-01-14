<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





//Approval controller
Route::get('/', 'ApprovalController@view')->name('Approval');
Route::post('/save-purchase-old-invoice', 'ApprovalController@save_purchaseOLD');
Route::post('/save-purchase', 'ApprovalController@save_purchase')->name('save_purchase');
Route::get('/add-Approval', 'ApprovalController@index')->name('ApprovalPurchase-purchase');
Route::get('/Approval-manage', 'ApprovalController@view')->name('home');
Route::get('/view-Approval-details/{ID}', 'ApprovalController@viewDetails');
Route::post('/update-Staff-details', 'ApprovalController@updateStaffDetails');


//HR controller
Route::get('/HR', 'HRController@index')->name('HRMangement');
Route::get('/view-HR/{ID}', 'HRController@view_HR');
Route::post('/update-HR', 'HRController@update_info');
Route::get('/published-HR/{ID}', 'HRController@published_HR');
Route::get('/unpublished-HR/{ID}', 'HRController@unpublished_HR');
Route::post('/save-HR', 'HRController@save_HR');
Route::get('/getAllHR', 'HRController@getAllHR');
Route::post('/save-HRAJAX', 'HRController@save_HRAJAX');


//HRPayment
Route::get('/HRPayment', 'HRController@viewPayment');
Route::get('/HRPaymentDetails/{ID}', 'HRController@paymentDetails');
Route::post('/save-HR-payment', 'HRController@save_HR_payment');


//Settings route controller
//brand

Route::get('/settings', 'SettingsController@index');
Route::post('/save-brand', 'SettingsController@save_brand');
Route::get('/published-brand/{StaffId}', 'SettingsController@published_brand');
Route::get('/unpublished-brand/{StaffId}', 'SettingsController@unpublished_brand');
Route::post('/update-brand-info', 'SettingsController@update_brand_info');


//Staff Category -STYLE
Route::get('/get-brand', 'SettingsController@getBrand');

Route::get('/get-style', 'StaffController@get');
Route::post('/save-style', 'StaffController@save');
Route::get('/published-style/{ID}', 'StaffController@publish');
Route::get('/unpublished-style/{ID}', 'StaffController@unpublish');
Route::post('/update-style-info', 'StaffController@update');


Route::get('/makepdfpurchase/{ID}', 'ApprovalController@pdf');
