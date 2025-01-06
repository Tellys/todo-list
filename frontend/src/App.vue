<template>
  <router-view />
  <LoaderComponent></LoaderComponent>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  components: {
    LoaderComponent: defineAsyncComponent(() =>
      import('@/components/LoaderComponent.vue')
    ),
  },
  name: "App",
};
</script>

<style>
.visible {
  visibility: visible !important;
}

.invisible {
  visibility: hidden !important;
}
</style>