<template>

  <TemplateTop></TemplateTop>

  <slot name="slotTitlePage"></slot>

  <div class="d-flex flex-column container-fluid min-vh-100">

    <div>
      <slot name="slotPageComponet"></slot>
    </div>

    <div class="mt-auto">
      <TemplateRodape />
    </div>
  </div>

  <LoaderComponent :is-visible="isLoading"></LoaderComponent>

</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'templateView',
  components: {
    TemplateRodape: defineAsyncComponent(() =>
      import('@/views/dashboard/TemplateRodape')
    ),
    TemplateTop: defineAsyncComponent(() =>
      import('@/views/dashboard/TemplateTop.vue')
    ),
    LoaderComponent: defineAsyncComponent(() =>
      import('@/components/LoaderComponent')
    ),
  },
  data() {
    return {
      isLoading: false
    }
  },
}
</script>

<style>
@import '@/assets/css/style.css';
</style>
