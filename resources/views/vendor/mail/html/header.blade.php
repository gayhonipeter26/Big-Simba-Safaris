@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'Big Simba Safari Security')
<img src="{{ asset('images/logo.png') }}" class="logo" alt="Big Simba Safaris">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
