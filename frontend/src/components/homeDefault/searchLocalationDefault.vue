<template>
    <form @submit.prevent="formSubmit">
        <div class="input-group">
            <!-- <button type="button" class="btn border border-end-0"><i class="bi bi-geo-alt"></i></button> -->
            <input v-model="register.query" id="autocompleteInput" type="text" class="form-control border border-end-0"
                placeholder="Selecione uma Cidade, Bairro etc" aria-label="Selecione uma Cidade, Bairro etc"
                aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary border border-start-0" type="submit" id="button-addon2"><i
                    class="bi bi-search"></i></button>
        </div>
    </form>
</template>

<script>
import Api from '@/services/Api';
//import store from '@/store';

export default {
    name:'searchLocalationDefault',
    data() {
        return {
            autocomplete: null,
            register: {},
            dataAutoComplete: null,
        };
    },
    async mounted() {
        await this.loadGoogleMaps().then(() => {
            this.initAutocomplete();
        });
    },
    methods: {
        ///
        async formSubmit() {
            let query = this.register?.query ?? '';
            return await Api.post('tennis-court/filter-items/' + query, this.dataAutoComplete).then((response) => {
                //localStorage.setItem("tennisCourtItems", JSON.stringify(response));
                ////console.log('localStorage.getItem("tennisCourtItems")', localStorage.getItem("tennisCourtItems"))
                // store.commit('tennisCourt/SET_ITEMS_BKP', JSON.parse(localStorage.getItem("tennisCourtItems")));
                // store.commit('tennisCourt/SET_ITEMS', response);
                console.log(response)
            }).then(() => {
                //return this.$router.push('/tennis-court/play-load');
            });

        },
        // Load Google Maps API
        async loadGoogleMaps() {
            return await new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.async = true;
                script.src = `https://maps.googleapis.com/maps/api/js?key=${process.env.VUE_APP_GOOGLE_MAPS}&libraries=places`;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        },
        async initAutocomplete() {
            // const google = new Loader({
            //     apiKey: process.env.VUE_APP_GOOGLE_MAPS,
            //     version: "weekly",
            //     libraries: ["places"]
            // });
            const options = {
                //fields: ["formatted_address", "geometry", "name"],
                //types: ['geocode'],
                //strictBounds: false,
            };

            const input = document.getElementById('autocompleteInput');
            this.autocomplete = await new window.google.maps.places.Autocomplete(input, options /* { types: ['(cities)'], } */);
            this.autocomplete.addListener('place_changed', this.onPlaceChanged);
            this.autocomplete.setComponentRestrictions({ 'country': 'BR' })
        },
        onPlaceChanged() {
            const place = this.autocomplete.getPlace();
            this.dataAutoComplete = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            }

            for (var i = 0; i < place.address_components.length; i++) {
                for (var j = 0; j < place.address_components[i].types.length; j++) {
                    switch (place.address_components[i].types[j]) {
                        case 'street_number':
                            this.dataAutoComplete['address_num'] = place.address_components[i].long_name
                            break;
                        case 'route':
                            this.dataAutoComplete['address'] = place.address_components[i].long_name
                            break;
                        case 'sublocality_level_1':
                        case 'sublocality':
                            this.dataAutoComplete['address_neighborhood'] = place.address_components[i].long_name
                            break;
                        case 'administrative_area_level_2':
                            this.dataAutoComplete['city'] = place.address_components[i].long_name
                            break;
                        case 'administrative_area_level_1':
                            this.dataAutoComplete['state'] = place.address_components[i].long_name
                            this.dataAutoComplete['state_code'] = place.address_components[i].short_name
                            break;
                        case 'country':
                            this.dataAutoComplete['country'] = place.address_components[i].long_name
                            this.dataAutoComplete['country_code'] = place.address_components[i].short_name
                            break;
                        case 'postal_code':
                            this.dataAutoComplete['zip_code'] = place.address_components[i].long_name
                            break;

                        default:
                            break;
                    }
                }
            }
            //console.log('place', place)
            //console.log('this.dataAutoComplete',this.dataAutoComplete)
        }
    }
}
</script>