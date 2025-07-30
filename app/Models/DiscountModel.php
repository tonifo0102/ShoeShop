<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountModel extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'code',
        'percent',
        'created_at',
        'updated_at'
    ];

    public static function getRemaining($discount)
    {
        $now = date('Y-m-d H:i:s');
        if ($discount->start_at > $now) {
            $diff = strtotime($discount->start_at) - strtotime($now);
            $days = floor($diff / (60 * 60 * 24));
            $hours = floor(($diff - $days * (60 * 60 * 24)) / (60 * 60));
            $minutes = floor(($diff - $days * (60 * 60 * 24) - $hours * (60 * 60)) / 60);
            $time = '';
            if ($days > 0) {
                $time .= $days . ' ngày ';
            }
            if ($hours > 0) {
                $time .= $hours . 'h';
            }
            if ($minutes > 0) {
                $time .= $minutes . 'm';
            }
            return 'Chưa bắt đầu: ' . $time;
        }

        if ($discount->end_at < $now) {
            return 'Hết hạn';
        }

        $diff = strtotime($discount->end_at) - strtotime($now);
        $days = floor($diff / (60 * 60 * 24));
        $hours = floor(($diff - $days * (60 * 60 * 24)) / (60 * 60));
        $minutes = floor(($diff - $days * (60 * 60 * 24) - $hours * (60 * 60)) / 60);
        $time = '';
        if ($days > 0) {
            $time .= $days . ' ngày ';
        }
        if ($hours > 0) {
            $time .= $hours . 'h';
        }
        if ($minutes > 0) {
            $time .= $minutes . 'm';
        }
        return 'Còn lại: ' . $time;
    }
}
