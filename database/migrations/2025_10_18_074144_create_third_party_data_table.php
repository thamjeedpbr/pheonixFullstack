<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('third_party_data', function (Blueprint $table) {
            $table->id();
            $table->string('UnqId')->nullable();
            $table->string('JobBagNo')->nullable();
            $table->string('MachineName')->nullable();
            $table->string('MachineDetails')->nullable();
            $table->string('Section')->nullable();
            $table->string('Form')->nullable();
            $table->string('SideID')->nullable();
            $table->string('Duration')->nullable();
            $table->string('PrintCopies')->nullable();
            $table->string('NofPlates')->nullable();
            $table->string('SheetQty')->nullable();
            $table->string('ExpDelDate')->nullable();
            $table->string('Clientname')->nullable();
            $table->string('Productname')->nullable();
            $table->string('Title')->nullable();
            $table->string('Reason')->nullable();
            $table->string('EqpName')->nullable();
            $table->string('PlannerDeliverydate')->nullable();
            $table->string('OrderBy')->nullable();
            $table->string('MachineID')->nullable();
            $table->string('PlanOrder')->nullable();
            $table->string('PlanDate')->nullable();
            $table->string('ProcessName')->nullable();
            $table->string('mps_objid')->nullable();
            $table->string('Qut_id')->nullable();
            $table->string('Section_name')->nullable();
            $table->string('Selected_item')->nullable();
            $table->string('Remarks')->nullable();
            $table->string('Planstatus_color')->nullable();
            $table->string('MRequiredImpression')->nullable();
            $table->string('DeliverdQty')->nullable();
            $table->string('LotNo')->nullable();
            $table->string('PlannedQty')->nullable();
            $table->string('SheetstobeProcessed')->nullable();
            $table->string('Balancetobeprocess')->nullable();
            $table->string('Ups')->nullable();
            $table->string('Labels')->nullable();
            $table->string('Statusid')->nullable();
            $table->string('AllDay')->nullable();
            $table->string('ProcessTypes')->nullable();
            $table->string('RecurrenceInfo')->nullable();
            $table->string('ReminderInfo')->nullable();
            $table->string('Statusstring')->nullable();
            $table->string('RequiredDatetime')->nullable();
            $table->string('AprovedDate')->nullable();
            $table->string('FlatDelivery')->nullable();
            $table->string('ActProcIId')->nullable();
            $table->timestamps();
            
            $table->index('UnqId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('third_party_data');
    }
};
