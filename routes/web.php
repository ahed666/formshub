<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DevicePlayerController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\App;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use App\Events\FormUpdated;
use Laravel\Fortify\RoutePath;

// Route::get('/', function () {
//     return redirect()->away('https://formshub.net');
// });


Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

Route::get('/invoice/download/{id}',[SubscriptionController::class, 'downloadInvoice'])->name('invoice.download');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // stripe
    Route::post('/subscription-checkout', [SubscriptionController::class, 'subscriptionCheckout'])->name('subscription.checkout');
    Route::get('/invoices', [SubscriptionController::class, 'invoices'])->name('invoices.index');
    Route::get('/getplans', [SubscriptionController::class, 'getPlans'])->name('getPlans');
    Route::get('/getcurrentsubscriptiondetails', [SubscriptionController::class, 'getSubscriptionDetails'])->name('subscription.current');

    // cancel subscription
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscriptionByid']);
    // resume subscription
    Route::post('/subscription/resume', [SubscriptionController::class, 'resumeSubscription']);

    // update allow auto renew for subscription 
    Route::post('/subscription/updateautorenew', [SubscriptionController::class, 'updateAutoRenew']);


    //on success payments
    Route::get('/subscription/payment/success',[SubscriptionController::class, 'subscriptionPaymentSuccess'])->name('subscription.success');
    // bill now 
    

    
    Route::get('/subscriptions', function () {
        return view('subscriptions.index');
    })->name('subscriptions.index');

    Route::get('/canusermakeaction/{action}', [SubscriptionController::class, 'canUserMakeAction'])->name('subscription.canmake');


    // profile

    Route::get('/countries', [ProfileController::class, 'getCountries'])->name('countries');
    Route::get('/getuser', [ProfileController::class, 'getUser'])->name('getUser');
    Route::post('/customer/update-billing', [ProfileController::class, 'updateBilling'])->name('customer.updateBilling');


    // language
    Route::get('/lang', [LocaleController::class, 'setLocale'])
    ->name('change.language');    



    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    




    Route::get('/devices',[DeviceController::class, 'index'])->name('devices');

    // delete device
    Route::delete('/deletedevices/{deviceId}',[DeviceController::class, 'deleteDevice'])->name('delete_device');

    // action on device player
    // send refresh event to device page
    Route::get('/refresh-device/{deviceCode}', [DeviceController::class, 'refreshDevice']);
    // restart device
    Route::get('/restart-device/{deviceCode}', [DeviceController::class, 'restartDevice']);



    // check if device is avilable to add by pin
    Route::get('/check-avilable-device/{pin}', [DeviceController::class, 'checkDevice']);

    // add device
    Route::post('/add-device', [DeviceController::class, 'addDevice']);

    // edit device
    Route::post('/edit-device', [DeviceController::class, 'editDevice']);


   


    // forms

    Route::get('/forms', [FormController::class, 'index'])->name('forms'); //Form index
    Route::get('/editform/{slug}', [FormController::class, 'editForm'])->name('form.edit'); //edit Form
    Route::post('/createform', [FormController::class, 'createForm'])->name('form.create');  //create Form
    Route::delete('/deleteform/{slug}', [FormController::class, 'deleteForm'])->name('form.delete');  //create Form
    Route::get('/getforms', [FormController::class, 'getForms']); //get all Forms of user
    Route::get('/formInfo/{slug}', [FormController::class, 'getFormInfo']); //get form info

    Route::get('/forms/statistics/{slug}', [FormController::class, 'StatisticsForm'])->name('form.statistics'); //statistics of form
    
    Route::post('/statistics/question/export', [FormController::class, 'exportQuestion'])->name('form.statistics.question.export'); //statisitcs question export
    Route::post('/statistics/responses/export', [FormController::class, 'exportResponses'])->name('form.statistics.responses.export'); //statisitcs question export

    

    Route::get('/getFormsfordevice/{device_id}', [DeviceController::class, 'getFormsPerDevice']); //get all Forms of device



    Route::get('/questioncategories', [FormController::class, 'getQuestionsCategories']); //get all questions categories


    Route::post('/uploadmedia', [FormController::class, 'uploadMedia']); //upload media
    Route::post('/deletemedia', [FormController::class, 'deleteMedia']); //delete media
    Route::post('/savechangesonform', [FormController::class, 'saveChangesOnForm']); //save changes on Form
    Route::post('/uploadFormaudio', [FormController::class, 'uploadFormAudio']); //upload audio of bg music for Form



    Route::put('/editmedianame', [FormController::class, 'editMediaName']); //edit media name
    Route::put('/editFormname', [FormController::class, 'editFormName']); //edit Form name



    //
});


// generate code and pin for device .. called from device-player
Route::post('/generate-code', [DeviceController::class, 'generateDeviceCodeWithPin']);

Route::get('/device-player/{id}/getcontent', [DevicePlayerController::class, 'getContent']);

Route::get('/device-player', [DevicePlayerController::class, 'index']);

Route::get('/get-assets-list', function () {
    $files = glob(public_path('build/assets/*'));
    $urls = array_map(function($file) {
        return str_replace(public_path(), '', $file);
    }, $files);

    return response()->json($urls);
});
