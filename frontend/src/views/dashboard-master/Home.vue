<template>

  <templateView>

    <template v-slot:slotTitlePage>
      <div class="p-2" style="background-color: silver;">
        <h1><i class="bi bi-database-fill-dash me-2"></i>Dashboard Master</h1>
        <p>Painel de controle Master</p>
      </div>
    </template>

    <template v-slot:slotPageComponet>

      <section class="container">

        <div class="row">

        </div>

      </section>
    </template>
  </templateView>
</template>

<script>
import templateView from '@/views/dashboard/Template.vue';

export default {
  name: 'homeView',
  components: {
    templateView,

  },

  mounted() {
    this.getItens();
  },

  methods: {
  }//fim

}
</script>

<style scoped>
svg {
  fill: red;
}
</style>