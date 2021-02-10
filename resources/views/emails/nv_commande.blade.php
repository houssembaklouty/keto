
@component('mail::message')

@component('mail::panel')

# Bonjour vous avez une nouvelle commande : 

first_name: {{ $first_name }} <br>
last_name: {{ $last_name }} <br>
country: {{ $country }} <br>
city: {{ $state }} <br>
email_address: {{ $email_address }} <br>
address: {{ $address }} <br>
phone_number: {{ $phone_number }} <br>
product: {{ $product }} <br>
zip_code: {{ $zip_code }} <br>

@endcomponent

@endcomponent