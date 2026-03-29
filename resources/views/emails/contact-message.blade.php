@extends('layouts.mail')

@section('content')
<div style="max-width:720px; margin:0 auto; padding:32px 16px;">
    <div style="background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(17,24,39,0.08);">
        <div style="padding:28px 32px; background:linear-gradient(135deg, #7c3aed, #f59e0b); color:#ffffff;">
            <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase;">
                Nouveau Message
            </p>
            <h1 style="margin:0; font-size:28px; line-height:1.2;">
                {{ $data['subject'] }}
            </h1>
        </div>

        <div style="padding:32px;">
            <div style="display:grid; gap:16px; margin-bottom:24px;">
                <div style="padding:18px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px;">
                    <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280;">Nom</p>
                    <p style="margin:0; font-size:18px; font-weight:700;">{{ $data['name'] }}</p>
                </div>
                <div style="padding:18px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px;">
                    <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280;">Email</p>
                    <p style="margin:0; font-size:16px;"><a href="mailto:{{ $data['email'] }}" style="color:#7c3aed; text-decoration:none;">{{ $data['email'] }}</a></p>
                </div>
                @if(!empty($data['phone']))
                    <div style="padding:18px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px;">
                        <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280;">Téléphone</p>
                        <p style="margin:0; font-size:16px;">{{ $data['phone'] }}</p>
                    </div>
                @endif
                @if(!empty($data['source_page']))
                    <div style="padding:18px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:16px;">
                        <p style="margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280;">Page source</p>
                        <p style="margin:0; font-size:14px; color:#374151;">{{ $data['source_page'] }}</p>
                    </div>
                @endif
            </div>

            <div style="padding:20px; background:#111827; border-radius:16px;">
                <p style="margin:0 0 12px; font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#cbd5e1;">Message</p>
                <div style="font-size:16px; line-height:1.7; color:#ffffff; white-space:pre-line;">{{ $data['message'] }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
