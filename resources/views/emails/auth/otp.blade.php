<x-mail::message>
# Operational Clearance Required

Greetings from the Pride. You are attempting to access high-clearance Safari Records. 

Please use the following **One-Time Password** to authenticate your entry:

<x-mail::panel>
## {{ $otp }}
</x-mail::panel>

Alternatively, you can approve this request directly from your primary mobile device:

<x-mail::button :url="route('otp.handshake', ['token' => $token])">
Authorize Access
</x-mail::button>

*This code and link will expire in 10 minutes. If you did not request this access, please secure your credentials immediately.*

Safe travels,<br>
**Big Simba Safari Security Team**
</x-mail::message>
