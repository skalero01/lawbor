<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait EnforcesPasswordRequests
{
    public function shouldEnforcePasswordRequest(): bool
    {
        return $this->getRequestedPasswordResetAt() > $this->getPasswordResetAt();
    }

    public function requestForNewPassword()
    {
        $this->requested_password_reset_at = now();
        $this->save();
    }

    public function getRequestedPasswordResetAt(): ?Carbon
    {
        return $this->requested_password_reset_at;
    }

    public function getPasswordResetAt(): ?Carbon
    {
        return $this->password_reset_at;
    }

    public function updatePasswordResetAt(Carbon $carbon)
    {
        $this->password_reset_at = $carbon;
    }
}
