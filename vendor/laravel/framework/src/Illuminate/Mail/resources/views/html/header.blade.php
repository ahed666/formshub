@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('/images/logo/app/main-logo.png')}}" class="logo" alt="formshub Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
