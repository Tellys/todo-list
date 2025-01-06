<template>
    <form @submit.prevent="formSubmit()" class="w-100 mx-2">
        <div class="input-group">
            <!-- <button type="button" class="btn border border-end-0"><i class="bi bi-geo-alt"></i></button> -->
            <input v-model="register.query" id="autoCompleteInput" type="text" class="form-control border border-end-0"
                placeholder="Qual Cidade, Bairro etc?" aria-label="Selecione uma Cidade, Bairro etc"
                aria-describedby="btnAutoCompleteInput">
            <button class="btn btn-outline-secondary border border-start-0" type="submit" id="btnAutoCompleteInput">
                <i class="bi bi-search" id="iconBtnAutoCompleteInput"></i>
                <span class="spinner-border spinner-border-sm d-none" id="spinnerBtnAutoCompleteInput"></span>
            </button>
        </div>
    </form>
</template>

<script>
import Api from '@/services/Api';
import { mapMutations } from 'vuex';

export default {
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
        ...mapMutations('tennisCourt',['SET_MY_LOCATION']),

        ///
        async formSubmit() {
            let btnAutoCompleteInput = document.getElementById('btnAutoCompleteInput');
            let iconBtnAutoCompleteInput = document.getElementById('iconBtnAutoCompleteInput');
            let spinnerBtnAutoCompleteInput = document.getElementById('spinnerBtnAutoCompleteInput');

            btnAutoCompleteInput.disabled = true
            iconBtnAutoCompleteInput.classList.add('d-none')
            spinnerBtnAutoCompleteInput.classList.remove('d-none')

            let query = this.register?.query ?? '';
            
            Api.post('tennis-court/close-to-me/' + query, this.dataAutoComplete).then((response) => {
                this.SET_MY_LOCATION(this.dataAutoComplete);
                console.log(response)
            }).then(() => {
                btnAutoCompleteInput.disabled = false
                iconBtnAutoCompleteInput.classList.remove('d-none')
                spinnerBtnAutoCompleteInput.classList.add('d-none')

                return this.$router.push('/tennis-court/play-load');
            })
        },

        // Load Google Maps API
        async loadGoogleMaps() {

            if (document.getElementById('fileGoogleapis')) {
                return
            }

            return await new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.id = 'fileGoogleapis';
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

            const input = document.getElementById('autoCompleteInput');
            this.autocomplete = await new window.google.maps.places.Autocomplete(input, options /* { types: ['(cities)'], } */);
            this.autocomplete.addListener('place_changed', this.onPlaceChanged);
            this.autocomplete.setComponentRestrictions({ 'country': 'BR' })
        },
        onPlaceChanged() {
            const place = this.autocomplete.getPlace();

            if (place?.geometry?.location) {

                this.dataAutoComplete = {
                    latitude: place.geometry.location.lat(),
                    longitude: place.geometry.location.lng(),
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
            }
        }
    }
}
</script>

<style scoped lang="css">

#btnAutoCompleteInput,#autoCompleteInput{border: 0 !important;}

</style>