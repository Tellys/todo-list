<template>
  <templateView>
    <template v-slot:slotPageComponet>
      <h1>Pagina LogOut</h1>
      <p>Você não esta mais logado</p>
      <p>Clique aqui para <router-link class="btn btn-success" to="/login">login</router-link></p>
    </template>
  </templateView>
</template>

<script>

import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';

// @ is an alias to /src
export default {
  name: 'LogOutView',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
  },
  data() {
    return {
      error: "",
      data: [],
    }
  },
  mounted() {
    this.logout();
  },
  methods: {
    isLoggedIn: () => {
      return true;// Api.isLoggedIn()
    },
    async logout() {
      if (this.isLoggedIn()) {
        return await Api.logout();
      }
    },
  }
}
</script>