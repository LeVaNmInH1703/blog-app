<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    protected function useTransaction($block)
    {
        DB::beginTransaction();
        try {
            if (is_callable($block)) {
                $block();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return true;
    }
}
