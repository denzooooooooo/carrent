@extends('layouts.app')

@section('title', 'Detail de reservation - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-amber-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto space-y-6">
            <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-purple-600">Reservation</p>
                        <h1 class="mt-2 text-3xl font-black text-gray-900">{{ $booking->title }}</h1>
                        <p class="mt-2 text-gray-600">Reference {{ $booking->booking_number }}</p>
                    </div>
                    <div class="rounded-2xl bg-purple-50 border border-purple-100 px-5 py-4 text-right">
                        <p class="text-sm text-gray-500">Montant paye</p>
                        <p class="text-2xl font-black text-purple-700">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</p>
                        <p class="mt-1 text-sm text-gray-500">{{ ucfirst($booking->status) }} • {{ ucfirst($booking->payment_status) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900">Informations client</h2>
                        <dl class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl bg-gray-50 px-5 py-4">
                                <dt class="text-sm text-gray-500">Nom</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $booking->customer_name }}</dd>
                            </div>
                            <div class="rounded-2xl bg-gray-50 px-5 py-4">
                                <dt class="text-sm text-gray-500">Email</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $booking->customer_email ?? 'Non renseigne' }}</dd>
                            </div>
                            <div class="rounded-2xl bg-gray-50 px-5 py-4">
                                <dt class="text-sm text-gray-500">Telephone</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $booking->customer_phone ?? 'Non renseigne' }}</dd>
                            </div>
                            <div class="rounded-2xl bg-gray-50 px-5 py-4">
                                <dt class="text-sm text-gray-500">Date de service</dt>
                                <dd class="mt-1 font-semibold text-gray-900">{{ $booking->travel_date_label ?? 'Non renseignee' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900">Detail de la reservation</h2>
                        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr>
                                        <td class="px-5 py-4 text-gray-500">Type</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ ucfirst($booking->booking_type) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4 text-gray-500">Reference</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $booking->booking_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4 text-gray-500">Date de reservation</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ optional($booking->created_at)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4 text-gray-500">Mode de paiement</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $booking->payment_method_label }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-5 py-4 text-gray-500">Transaction</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $booking->payment?->transaction_id ?? ($booking->payment_transaction_id ?: 'Non renseignee') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900">Documents</h2>
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'invoice']) }}" class="flex items-center justify-between rounded-2xl border border-purple-200 bg-purple-50 px-5 py-4 text-purple-900 transition hover:border-purple-300 hover:bg-purple-100">
                                <span>
                                    <span class="block text-sm text-purple-600">Facture PDF</span>
                                    <span class="block font-semibold">{{ $booking->invoice_number ?? 'Disponible' }}</span>
                                </span>
                                <span class="text-sm font-bold">Telecharger</span>
                            </a>
                            <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'receipt']) }}" class="flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-950 transition hover:border-amber-300 hover:bg-amber-100">
                                <span>
                                    <span class="block text-sm text-amber-700">Recu PDF</span>
                                    <span class="block font-semibold">{{ $booking->receipt_number ?? 'Disponible' }}</span>
                                </span>
                                <span class="text-sm font-bold">Telecharger</span>
                            </a>
                        </div>

                        <form action="{{ route('user.booking.resend-documents', $booking) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl bg-gray-900 px-5 py-4 text-sm font-bold text-white transition hover:bg-black">
                                Renvoyer les documents par email
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900">Montants</h2>
                        <div class="mt-6 space-y-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-5 py-4">
                                <span class="text-gray-500">Montant de base</span>
                                <span class="font-semibold text-gray-900">{{ number_format((float) $booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-5 py-4">
                                <span class="text-gray-500">Reduction</span>
                                <span class="font-semibold text-gray-900">-{{ number_format((float) $booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-5 py-4">
                                <span class="text-gray-500">Taxes</span>
                                <span class="font-semibold text-gray-900">{{ number_format((float) $booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-purple-700 px-5 py-4 text-white">
                                <span class="font-semibold">Total</span>
                                <span class="text-lg font-black">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
