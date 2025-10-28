<?php

namespace App\Actions\Fortify;

use App\Models\Staff;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(Staff $staff, array $input): void
    {
        Validator::make($input, [
            'user_name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Staff::class)->ignore($staff->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $staff->email &&
            $staff instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($staff, $input);
        } else {
            $staff->forceFill([
                'user_name' => $input['user_name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(Staff $staff, array $input): void
    {
        $staff->forceFill([
            'user_name' => $input['user_name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $staff->sendEmailVerificationNotification();
    }
}
