<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLedger;

class UserRepo {
    public static function ledger($user_id, $debit, $credit, $details = null, $invoice_no_1 = null, $invoice_no_2 = null){
        $last_ledger = UserLedger::where('user_id', $user_id)->latest('id')->first();

        if($last_ledger){
            $previous_balance = $last_ledger->current_balance;
            $total_debit = $last_ledger->total_debit + $debit;
            $total_credit = $last_ledger->total_credit + $credit;
        }else{
            $previous_balance = 0;
            $total_debit = $debit;
            $total_credit = $credit;
        }
        $current_balance = $total_debit - $total_credit;

        // Create Ledger
        $ledger = new UserLedger();
        $ledger->user_id = $user_id;
        $ledger->invoice_no_1 = $invoice_no_1;
        $ledger->invoice_no_2 = $invoice_no_2;
        $ledger->description = $details;
        $ledger->debit = $debit;
        $ledger->credit = $credit;
        $ledger->previous_balance = $previous_balance;
        $ledger->current_balance = $current_balance;
        $ledger->total_debit = $total_debit;
        $ledger->total_credit = $total_credit;
        $ledger->save();

        // Update User
        $user = User::find($user_id);
        if($user){
            $user->balance = $current_balance;
            $user->save();
        }

        return $ledger;
    }
}
