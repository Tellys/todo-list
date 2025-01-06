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
// @ is an alias to /src
import TemplateTop from './TemplateTop.vue'
import TemplateRodape from './TemplateRodape'
import LoaderComponent from '@/components/LoaderComponent'

export default {
  name: 'templateView',
  components: {
    TemplateRodape,
    TemplateTop,
    LoaderComponent,

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
