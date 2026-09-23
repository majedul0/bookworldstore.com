<?php

namespace App\Repositories;

use App\Models\Accounts\Accounts;
use App\Models\Accounts\Expense;
use App\Models\Accounts\Income;

class AccountsRepo {
    public static function expense($amount, $note = null){
        $expense = new Expense;
        $expense->amount = $amount;
        $expense->note = $note;
        $expense->save();

        return $expense;
    }

    public static function income($amount, $note = null){
        $income = new Income;
        $income->amount = $amount;
        $income->note = $note;
        $income->save();

        return $income;
    }

    public static function accounts($type, $amount, $note = null){
        $last = Accounts::latest('id')->first();
        $previous_balance = $last->current_balance ?? 0;
        // Current Balance
        if($type == 'Credit'){
            $current_balance = $previous_balance + $amount;
        }else{
            $current_balance = $previous_balance - $amount;
        }

        $accounts = new Accounts;
        $accounts->type = $type;
        $accounts->amount = $amount;
        $accounts->previous_balance = $previous_balance;
        $accounts->current_balance = $current_balance;
        $accounts->note = $note;
        $accounts->save();

        return $accounts;
    }
}
