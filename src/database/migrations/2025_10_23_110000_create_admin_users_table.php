<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('is_admin_users', function (Blueprint $table) {
        $table->id();

        // 外部キー：staff.id
        $table->unsignedBigInteger('staff_id');

        // 外部キー：staff.employee_number（varchar型）
        $table->string('employee_number');

        // 管理者フラグ
        $table->boolean('is_admin')->default(false);

        $table->timestamps();

        // 外部キー制約（staff.id）
        $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');

        // 外部キー制約（staff.employee_number）
        $table->foreign('employee_number')->references('employee_number')->on('staff')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
