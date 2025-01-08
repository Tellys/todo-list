<template>

  <templateView>

    <template v-slot:slotTitlePage>
      <div class="p-2" style="background-color: silver;">
        <h1><i class="bi bi-database-fill-dash me-2"></i>Dashboard</h1>
        <p>Painel de controle da aplicação</p>
      </div>
    </template>

    <template v-slot:slotPageComponet>

      <section class="container">

<div class="row">

  <div class="my-3">
    <div class="row justify-content-center">
      <div class="d-flex align-items-center">
        <div class="p-2 rounded border shadow me-2" style="background-color: silver; ">
        </div>
        <div>Carrinho de Campras</div>
      </div>
    </div>

  </div>
</div>



</section>
    </template>
  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'homeView',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/dashboard/Template.vue')
    ),
  },

  mounted() {
    this.getItens();
  },

  methods: {
    async getItens() {
      return true;
    },
    async goToRoute(myRoute) {
      //this.$router.push({ name: 'user', params: { username: 'eduardo' } })
      this.$router.push({ name: myRoute })
    }
  }//fim

}
</script>

<style scoped>
svg {
  fill: red;
}
</style>