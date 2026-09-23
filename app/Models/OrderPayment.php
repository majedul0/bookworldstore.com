<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    public function getCardNumberSecreteAttribute(){
        if($this->card_number){
            $first = substr($this->card_number, 0, 1);
            $last = substr ($this->card_number, -4);

            return "$first***********$last";
        }
        return 'N/A';
    }
}
