New form submission: {{ $submission->formDefinition?->name }}

@foreach($submission->data ?? [] as $key => $value)
{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ is_array($value) ? json_encode($value) : $value }}
@endforeach

Submitted at: {{ $submission->created_at }}
