<template>

  <TemplateTop></TemplateTop>
  <slot name="slotTitlePage"></slot>

  <slot name="slotLineOne"></slot>

  <!-- <div class="d-flex flex-column container-fluid min-vh-100 mb-5">
    <slot name="slotPageComponet"></slot>
  </div> -->
  <slot name="slotPageComponet"></slot>
  
  <TemplateRodape />

  <LoaderComponent :is-visible="isLoading"></LoaderComponent>

  <slot name="slotLastLine"></slot>

</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'templateView',
  components: {
    LoaderComponent: defineAsyncComponent(() =>
      import('@/components/LoaderComponent')
    ),
    TemplateTop: defineAsyncComponent(() =>
      import('@/views/template/TemplateTop.vue')
    ),
    TemplateRodape: defineAsyncComponent(() =>
      import('@/views/template/TemplateRodape')
    ),
  },
  data() {
    return {
      isLoading: false,
      //coordinates: {}
    }
  },
  async mounted() {
    /*   await this.loadGoogleMaps().then(() => {
        this.getLocalization();
      }); */
  },
  methods: {
    /* async loadGoogleMaps() {
      return await new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.async = true;
        script.src = `https://maps.googleapis.com/maps/api/js?key=${process.env.VUE_APP_GOOGLE_MAPS}`;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
      });
    },
    getLocalization() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;
          var accuracy = position.coords.accuracy;
          var coords = new window.googlemaps.LatLng(latitude, longitude);
          var mapOptions = {
            zoom: 15,
            center: coords,
            mapTypeControl: true,
            navigationControlOptions: {
              style: window.googlemaps.NavigationControlStyle.SMALL
            },
            mapTypeId: window.googlemaps.MapTypeId.ROADMAP
          };

          var capa = document.getElementById("capa");
          capa.innerHTML = "latitude: " + latitude + ", longitude: " + ", accuracy: " + accuracy;

          map = new window.googlemaps.Map(document.getElementById("mapContainer"), mapOptions);
          var marker = new window.googlemaps.Marker({
            position: coords,
            map: map,
            title: "ok"
          });

        },
          function error(msg) { alert('Please enable your GPS position feature.'); },
          { maximumAge: 10000, timeout: 5000, enableHighAccuracy: true });
      } else {
        alert("Geolocation API is not supported in your browser.");
      }
    },

    async initMap() {
      //@ts-ignore
      const { Map } = await window.googlemaps.importLibrary("maps");

      map = new Map(document.getElementById("map"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
      });
    }, */
  },

}
</script>

<style lang="scss">
@import "@/assets/scss/style";
</style>
