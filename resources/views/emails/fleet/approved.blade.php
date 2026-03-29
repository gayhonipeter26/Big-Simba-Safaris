<x-mail::message>
# Logistics Deployment Authorized

Dear {{ $fleetHire->name }},

Your request to deploy **{{ $fleetHire->fleet->name }}** has been formally authorized by Big Simba Safaris operations command.

**Deployment Details:**
- **Asset:** {{ $fleetHire->fleet->name }} ({{ $fleetHire->fleet->drive_type }})
- **Commencement:** {{ \Carbon\Carbon::parse($fleetHire->start_date)->format('M d, Y') }}
- **Conclusion:** {{ \Carbon\Carbon::parse($fleetHire->end_date)->format('M d, Y') }}

Please check your dashboard for further intelligence and ensure all necessary logistics are prepared before commencement.

<x-mail::button :url="route('dashboard')">
View Deployment Log
</x-mail::button>

Stay wild,<br>
The Big Simba Command
</x-mail::message>
