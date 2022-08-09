<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ config('mitra.icon_text') }}" alt="{{ config('mitra.name') }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
