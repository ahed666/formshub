<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('timezone')->default('Asia\Dubai');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');



            $table->rememberToken();
            $table->foreignId('current_account_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();


            $table->string('mobile_number')->nullable();

            $table->boolean('email_sub_payment_subscriptions')->default(true);
            $table->boolean('email_sub_security')->default(true);
            $table->boolean('email_sub_offers_events')->default(true);
            $table->boolean('email_sub_notification')->default(true);
            $table->string('unsubscribe_token')->nullable();

            $table->string('billing_name')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_trn')->nullable();

            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
