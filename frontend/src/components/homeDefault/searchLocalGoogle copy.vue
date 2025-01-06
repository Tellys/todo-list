<template>
    <Vueform>
        <LocationElement name="location" addClass='vf-my-form'
            placeholder="Procurar por Localidade (Cidade, Bairro etc)" />
    </Vueform>
</template>

<script>
export default {
    name: 'searchLocalGoogle',
    data() {
        return {
            iconLocation: '<i class="bi bi-geo-alt"></i>',
        }
    },
}

</script>

<style lang="scss" scoped>
.vf-my-form *,
.vf-my-form *:before,
.vf-my-form *:after,
.vf-my-form:root {
    --vf-border-width-input-t: 0px;
    --vf-border-width-input-r: 0px;
    --vf-border-width-input-b: 0px;
    --vf-border-width-input-l: 0px;
}
</style>
<!-- 
    // Google

new window.google.maps.places.Autocomplete(container, {
  fields: ['geometry', 'formatted_address', 'address_components'],
  ...options
});
   -->