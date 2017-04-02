@include('helpers.google_address', [
    'address_string' => $wizardData['address_string'] ?? null,
    'city' => $wizardData['city'] ?? null,
    'zip_code' => $wizardData['zip_code'] ?? null,
    'street' => $wizardData['street'] ?? null,
])