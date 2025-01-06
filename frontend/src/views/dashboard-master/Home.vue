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
                <a href="#" @click="goToRoute('dashboardMasterTennisCourtTypeList')">Quadras - Tipos</a>
              </li>
              <li>
                <a href="#" @click="goToRoute('dashboardMasterTennisCourtGroupList')">Quadras - Grupos</a>
              </li>
              <li>
                <a href="#" @click="goToRoute('dashboardMasterTennisCourtDescritpionTableList')">Quadras - Itens
                  Descritivos
                </a>
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
                <a href="#" @click="goToRoute('dashboardMasterProductDepartmentList')">Departamentos de Produtos</a>
              </li>
              <li>
                <a href="#" @click="goToRoute('dashboardMasterProductDefaultList')">Produtos e Serviços <strong>(padrão)</strong></a>
              </li>
              <li>
                <a href="#" @click="goToRoute('dashboardMasterProductList')">Produtos e Serviços (geral)</a>
              </li>
              <li>
                <a href="#" @click="goToRoute('dashboardMasterDiscountPolicyList')">Políticas de Desconto</a>
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
// @ is an alias to /src
import IconFileSvg from '@/components/icons/IconFileSvg.vue';
import templateView from '@/views/dashboard/Template.vue';

export default {
  name: 'homeView',
  components: {
    templateView,
    IconFileSvg
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