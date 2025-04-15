<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\LeaveType;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            // Sử dụng đúng tên cột khóa chính của bảng employees là 'employee_id'
            $table->unsignedBigInteger('employee_id'); 
            $table->foreign('employee_id')->references('id')->on('employees'); // Tham chiếu đúng cột employee_id
            $table->enum('type', [
                LeaveType::ANNUAL,
                LeaveType::SICK,
                LeaveType::UNPAID,
                LeaveType::MATERNITY,
            ])->default(LeaveType::ANNUAL);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->useCurrent();
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('decision_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
};
