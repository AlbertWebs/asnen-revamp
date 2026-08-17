New event registration: {{ $event->title }}

Name: {{ $registration->name }}
Email: {{ $registration->email }}
Phone: {{ $registration->phone ?: 'Not given' }}
Organisation: {{ $registration->organization ?: 'Not given' }}
Notes: {{ $registration->notes ?: 'Not given' }}
Marketing consent: {{ $registration->consent_marketing ? 'Yes' : 'No' }}

Submitted at: {{ $registration->created_at }}
