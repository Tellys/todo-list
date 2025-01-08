<template>

  <div class="container-fluid min-vh-100 d-flex flex-column bgFull">

    <LoaderComponent :is-visible="isLoading"></LoaderComponent>

    <div class="d-flex bd-highlight">
      <div class="p-2 bd-highlight">
        <router-link to="/">
          <picture>
            <img src="@/assets/images/logo/logo.png" width="150"
              alt="{{ `${process.env.VUE_APP_NAME}` }}" />
          </picture>
        </router-link>
      </div>
    </div>

    <div>
      <slot name="slotPageComponet"></slot>
    </div>

  </div>
</template>

<script>
import LoaderComponent from '@/components/LoaderComponent'

export default {
  name: 'templateFullWView',
  components: {
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

.bgFull{
  background: url('@/assets/images/bg/bg.jpg') red no-repeat center center;
  background-size: cover;
}
</style>
