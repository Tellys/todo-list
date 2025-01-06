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
          <IconFileSvg icon="quadra" height="30px" width="30px" bgColor="#083B54"></IconFileSvg>
        </div>
        <div>Carrinho de Campras</div>
      </div>
    </div>

    <ul>
      <li>
        <a href="#" @click="goToRoute('dashboardCartShow')">Ver meu carrinho</a>
      </li>
    </ul>

  </div>

  <div class="my-3">
    <div class="row justify-content-center">
      <div class="d-flex align-items-center">
        <div class="p-2 rounded border shadow me-2" style="background-color: silver; ">
          <IconFileSvg icon="quadra" height="30px" width="30px" bgColor="#083B54"></IconFileSvg>
        </div>
        <div>Quadras</div>
      </div>
    </div>

    <ul>
      <li>
        <a href="#" @click="goToRoute('dashboardTennisCourtList')">Minhas Quadras</a>
      </li>
      <li>
        <a href="#" @click="goToRoute('dashboardTennisCourtInvolvementList')">Meus Envolvimentos (like / save)</a>
      </li>
    </ul>

  </div>

  <div class="my-3">
    <div class="row justify-content-center">
      <div class="d-flex align-items-center">
        <div class="p-2 rounded border shadow me-2"
          style="background-color: silver; min-height:30px; min-width:30px">
          <i class="bi bi-clipboard-minus"></i>
        </div>
        <div>Produtos e Serviços</div>
      </div>
    </div>

    <ul>
      <li>
        <a href="#" @click="goToRoute('dashboardProductList')">Produtos e Serviços</a>
      </li>
      <li>
        <a href="#" @click="goToRoute('dashboardDiscountPolicyList')">Políticas de Desconto</a>
      </li>

    </ul>

  </div>

  <div class="my-3">
    <div class="row justify-content-center">
      <div class="d-flex align-items-center">
        <div class="p-2 rounded border shadow me-2" style="background-color: silver; ">
          <IconFileSvg icon="player" height="30px" width="30px" bgColor="#083B54"></IconFileSvg>
        </div>
        <div>Times</div>
      </div>
    </div>
  </div>

  <div class="my-3">
    <div class="row justify-content-center">
      <div class="d-flex align-items-center">
        <div class="p-2 rounded border shadow me-2" style="background-color: silver; ">
          <IconFileSvg icon="player_trofeu" height="30px" width="30px" bgColor="#083B54"></IconFileSvg>
        </div>
        <div>Torneios</div>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-center mb-5">
  <!-- <img src="@/assets/banners/banner-exemplo.gif" alt="Banner Top" class="text-center img-fluid" style="max-width: 730px;"> -->
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
    IconFileSvg: defineAsyncComponent(() =>
      import('@/components/icons/IconFileSvg.vue')
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