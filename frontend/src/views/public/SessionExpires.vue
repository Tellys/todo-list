<template>
  <templateView>
    <template v-slot:slotPageComponet>
      <h1>Sessão Expirada</h1>
      <p>Sua sessão expirou</p>
      <p>Clique aqui para <router-link class="btn btn-success" to="/login">login</router-link></p>
    </template>
  </templateView>
</template>

<script>

import { defineAsyncComponent } from 'vue';

// @ is an alias to /src
export default {
  name: 'SessionExpiresView',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
  },
}
</script>