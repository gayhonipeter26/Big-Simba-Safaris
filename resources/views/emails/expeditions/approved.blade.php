<x-mail::message>
# Expedition Command Authorized

Dear {{ $tourEnquiry->name }},

Your requested expedition for **{{ $tourEnquiry->tour->name }}** has been officially authorized by Big Simba Safaris. 

**Mission Briefing:**
- **Destination:** {{ $tourEnquiry->tour->destination->name ?? 'Specified Location' }}
- **Commencement:** {{ \Carbon\Carbon::parse($tourEnquiry->start_date)->format('M d, Y') }}
- **Conclusion:** {{ \Carbon\Carbon::parse($tourEnquiry->end_date)->format('M d, Y') }}
- **Operatives:** {{ $tourEnquiry->guests }}
@if($tourEnquiry->fleet)
- **Attached Logistics:** {{ $tourEnquiry->fleet->name }}
@endif

We have prepared the way. Your dashboard contains full mission details. Ensure you are ready when the pulse quickens.

<x-mail::button :url="route('dashboard')">
Open Command Log
</x-mail::button>

Stay wild,<br>
The Big Simba Command
</x-mail::message>
