<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class BookingAccessService
{
    public function canUserAccess(Booking $booking, ?User $user): bool
    {
        if (Auth::guard('admin')->check()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($booking->user_id && (int) $booking->user_id === (int) $user->id) {
            return true;
        }

        $contactEmail = $this->contactEmail($booking);

        return $contactEmail !== null && $user->email === $contactEmail;
    }

    public function authorize(Request $request, Booking $booking): void
    {
        $user = Auth::user();

        if ($this->canUserAccess($booking, $user)) {
            return;
        }

        if (!$booking->user_id && $request->hasValidSignature()) {
            return;
        }

        abort(403);
    }

    public function bookingRoute(string $routeName, Booking $booking, array $parameters = [], int $expirationDays = 30): string
    {
        $parameters = array_merge($parameters, ['booking' => $booking]);

        if ($booking->user_id) {
            return route($routeName, $parameters);
        }

        return URL::temporarySignedRoute(
            $routeName,
            now()->addDays($expirationDays),
            $parameters
        );
    }

    public function contactEmail(Booking $booking): ?string
    {
        $booking->loadMissing(['eventBooking', 'locationBooking', 'packageBooking', 'user']);

        if ($booking->user?->email) {
            return $booking->user->email;
        }

        if ($booking->eventBooking?->user_email) {
            return $booking->eventBooking->user_email;
        }

        if ($booking->locationBooking?->user_email) {
            return $booking->locationBooking->user_email;
        }

        if ($booking->packageBooking?->participants_details) {
            $firstParticipant = $booking->packageBooking->participants_details[0] ?? null;
            if (!empty($firstParticipant['email'])) {
                return $firstParticipant['email'];
            }
        }

        if (!empty($booking->passenger_details[0]['email'])) {
            return $booking->passenger_details[0]['email'];
        }

        return null;
    }

    public function contactName(Booking $booking): string
    {
        $booking->loadMissing(['user', 'eventBooking', 'locationBooking', 'packageBooking']);

        if ($booking->user) {
            return trim(($booking->user->first_name ?? '') . ' ' . ($booking->user->last_name ?? '')) ?: 'Client';
        }

        if ($booking->eventBooking?->user_name) {
            return $booking->eventBooking->user_name;
        }

        if ($booking->locationBooking?->user_name) {
            return $booking->locationBooking->user_name;
        }

        if ($booking->packageBooking?->participants_details) {
            $firstParticipant = $booking->packageBooking->participants_details[0] ?? null;
            if ($firstParticipant) {
                return trim(($firstParticipant['first_name'] ?? '') . ' ' . ($firstParticipant['last_name'] ?? '')) ?: 'Client';
            }
        }

        if (!empty($booking->passenger_details[0])) {
            return trim(($booking->passenger_details[0]['first_name'] ?? '') . ' ' . ($booking->passenger_details[0]['last_name'] ?? '')) ?: 'Client';
        }

        return 'Client';
    }
}
