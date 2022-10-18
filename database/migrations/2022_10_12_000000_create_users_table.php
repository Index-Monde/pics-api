<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('profile_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('country')->nullable();
            $table->json('professional_portofolio')->nullable();
            $table->json('social')->nullable();
            $table->foreignId('setting_id')->constrained('settings');
            $table->json('stats')->nullable();
            $table->string('current_subscription_status');
            $table->string('email')->unique();
            $table->foreignId('role_id')->constrained('roles');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
