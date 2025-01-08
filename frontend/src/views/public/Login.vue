<template>

  <LoaderComponent :is-visible="isLoading"></LoaderComponent>
  <section class="container-fluid min-vh-100 bgFull">

    <div class="d-flex align-items-center flex-column justify-content-center min-vh-100 ">
      <div class="p-2">
        <router-link to="/">
          <picture>
            <img src="@/assets/images/logo/logo.png" width="200"
              alt="{{ `${process.env.VUE_APP_NAME}` }}" />
          </picture>
        </router-link>
      </div>

      <!-- <div class="mt-auto" v-if="this.$route.query?.redirect">
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          Faça o login para ter acesso aos conteúdos!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div> -->

      <div class="mt-auto col-md-7 col-lg-4">
        <div class="bg-white p-4 rounded-top shadow-lg ">
          <LoginComponent />
        </div>
      </div>
    </div>
  </section>

</template>

<script>
// @ is an alias to /src
import Swal from 'sweetalert2';
import router from "@/router";
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';

export default {
  name: 'LoginView',
  components: {
    LoaderComponent: defineAsyncComponent(() =>
      import('@/components/LoaderComponent.vue')
    ),
    LoginComponent: defineAsyncComponent(() =>
      import('@/components/LoginComponent.vue')
    ),
  },
  data() {
    return {
      isLoading: false,
      messageAlert: null, // case user Tried no auth
    }
  },
  mounted() {
    this.fnIsLoggedIn();
  },
  methods: {
    async fnIsLoggedIn() {
      if (Api.isLoggedIn('bool')) {
        Swal.fire({
          title: 'Atenção',
          text: 'Você já esta logado!',
          icon: 'warning',
        }).then(() => {
          router.push('/');
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/scss/style";

.bgFull {
  background: url('@/assets/images/bg/bg.jpg') red no-repeat center center;
  background-size: cover;
}
</style>