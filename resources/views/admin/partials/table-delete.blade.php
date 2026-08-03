{{--
  Table row delete action.
  Expects: $action (URL), $permission (ability), optional $label (confirm text subject)
--}}
@php
    $label = $label ?? 'this item';
    $confirm = 'Delete '.$label.'? This cannot be undone.';
@endphp
@can($permission)
    <form
        method="POST"
        action="{{ $action }}"
        class="admin-table__delete"
        onsubmit="return confirm(@js($confirm))"
    >
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-table__link admin-table__link--danger">Delete</button>
    </form>
@endcan
