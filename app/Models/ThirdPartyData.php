<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdPartyData extends Model
{
    use HasFactory;

    protected $table = 'third_party_data';

    protected $fillable = [
        'UnqId', 'JobBagNo', 'MachineName', 'MachineDetails', 'Section', 'Form', 'SideID', 'Duration',
        'PrintCopies', 'NofPlates', 'SheetQty', 'ExpDelDate', 'Clientname', 'Productname', 'Title', 'Reason',
        'EqpName', 'PlannerDeliverydate', 'OrderBy', 'MachineID', 'PlanOrder', 'PlanDate', 'ProcessName',
        'mps_objid', 'Qut_id', 'Section_name', 'Selected_item', 'Remarks', 'Planstatus_color',
        'MRequiredImpression', 'DeliverdQty', 'LotNo', 'PlannedQty', 'SheetstobeProcessed',
        'Balancetobeprocess', 'Ups', 'Labels', 'Statusid', 'AllDay', 'ProcessTypes', 'RecurrenceInfo',
        'ReminderInfo', 'Statusstring', 'RequiredDatetime', 'AprovedDate', 'FlatDelivery', 'ActProcIId'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
