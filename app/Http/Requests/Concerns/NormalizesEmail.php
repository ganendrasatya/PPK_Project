<?php

namespace App\Http\Requests\Concerns;

/**
 * Email diketik dengan huruf besar (mis. keyboard HP: "Budi@Gmail.com") dikecilkan
 * sebelum validasi, alih-alih ditolak oleh aturan "lowercase".
 */
trait NormalizesEmail
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }
}
