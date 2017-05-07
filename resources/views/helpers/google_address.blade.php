<p class="h3">Wyszuja adress</p>
<div class="form-group">
    <label for="address_string">Wyszukaj adres</label>
    <input id="address_string" type="text" class="form-control" name="address_string" value="{{ old('address_string', $address_string ?? '') }}" placeholder="Adres">
</div>
<p class="h3">Lub wypełnij</p>
<div>
    <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
        <label for="city">Miasto</label>
        <input id="city" type="text" class="form-control form-control-danger" name="city" value="{{ old('city', $city ?? '') }}" placeholder="Miasto">
        <div class="form-control-feedback">{{ $errors->first('city') }}</div>
    </div>
    <div class="form-group{{ $errors->has('zip_code') ? ' has-danger' : '' }}">
        <label for="zip_code">Kod pocztowy</label>
        <input id="zip_code" type="text" class="form-control form-control-danger" name="zip_code" value="{{ old('zip_code', $zip_code ?? '') }}" placeholder="Kod pocztowy">
        <div class="form-control-feedback">{{ $errors->first('zip_code') }}</div>
    </div>
    <div class="form-group{{ $errors->has('street') ? ' has-danger' : '' }}">
        <label for="street">Ulica</label>
        <input id="street" type="text" class="form-control form-control-danger" name="street" value="{{ old('street', $street ?? '') }}" placeholder="Ulica">
        <div class="form-control-feedback">{{ $errors->first('street') }}</div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&libraries=places" async defer></script>
    <script>
        (function ($) {
            $(function () {
                // This example displays an address form, using the autocomplete feature
                // of the Google Places API to help users fill in the information.
                var $address_string = $('#address_string');
                $address_string.on('focus', function () {
                    initAutocomplete();
                    geolocate()
                });

                $address_string.on('keydown', function (e) {
                    //prevent submit form when autocomplete container is visible
                    if (e.keyCode == 13 && $('.pac-container:visible').length) {
                        e.preventDefault();
                    }
                });

                var autocomplete;

                function initAutocomplete() {
                    // Create the autocomplete object, restricting the search to geographical
                    // location types.
                    autocomplete = new google.maps.places.Autocomplete(
                        /** @type {!HTMLInputElement} */(document.getElementById('address_string')),
                        {types: ['geocode']});
                    autocomplete.setComponentRestrictions({'country': 'PL'});

                    autocomplete.addListener('place_changed', fillAddressInputs);
                }

                function fillAddressInputs() {
                    // Get the place details from the autocomplete object.
                    var place = autocomplete.getPlace();

                    if (place.address_components) {
                        // Get each component of the address from the place details
                        // and fill the corresponding field on the form.
                        var street_number = '';
                        var route = '';
                        for (var i = 0; i < place.address_components.length; i++) {
                            var addressType = place.address_components[i].types[0];

                            if (addressType == 'street_number') {
                                street_number = place.address_components[i]['long_name'];
                            }

                            if (addressType == 'route') {
                                route = place.address_components[i]['long_name'];
                            }

                            if (addressType == 'postal_code') {
                                $('#zip_code').val(place.address_components[i]['short_name']);
                            }

                            if (addressType == 'locality') {
                                $('#city').val(place.address_components[i]['long_name']);
                            }

                        }

                        if (typeof route !== 'undefined' && route != '') {
                            var street = route;
                            if (street_number != '' && typeof street_number !== 'undefined') {
                                street += ' ' + street_number;
                            }
                            $('#street').val(street);
                            $('[for=street]').addClass('active');
                        }
                    }
                }

                // Bias the autocomplete object to the user's geographical location,
                // as supplied by the browser's 'navigator.geolocation' object.
                function geolocate() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var geolocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            var circle = new google.maps.Circle({
                                center: geolocation,
                                radius: position.coords.accuracy
                            });
                            autocomplete.setBounds(circle.getBounds());
                        });
                    }
                }
            });
        })(jQuery);
    </script>
@endsection