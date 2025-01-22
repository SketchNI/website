<?php

namespace App\Traits;

trait Flashable
{
    public function flash(string $message, string $type = 'success'): void
    {
        session()->flash('flash', ['message' => $message, 'type' => $type]);
    }
}
