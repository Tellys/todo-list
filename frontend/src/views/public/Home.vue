<template>
  <templateView>

    <template v-slot:slotLineOne>
    </template>

    <template v-slot:slotPageComponet>

      <section class="db-fill-home hero-section">

        <div class="container text-uppercase ">

          <div class="row px-4">

            <div class="col-6 col-md-4" role="button" @click="goToRoute('dashboardTaskCreate')">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded" style="background-color: #C3FF7F">
                  <i class="fa-solid fa-plus" style="color:#141414"></i>
                </div>
                <div class="text-center">Adicionar Tarefa</div>
              </div>
            </div>

            <div class="col-6 col-md-4" role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded" style="background-color: #FF7665">
                  <i class="fa-solid fa-calendar-day" style="color:#141414"></i>
                </div>
                <div class="text-center">Hoje</div>
              </div>
            </div>

            <div class="col-6 col-md-4 " role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded" style="background-color: #FFE897">
                  <i class="fa-regular fa-calendar-days" style="color:#141414"></i>
                </div>
                <div class="text-center">Em Breve</div>
              </div>
            </div>

            <div class="col-6 col-md-4" role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded" style="background-color:#feddcb">
                  <i class="fa-solid fa-tags" style="color:#141414"></i>
                </div>
                <div class="text-center">Filtros de etiquetas</div>
              </div>
            </div>

            <div class="col-6 col-md-4" role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded" style="background-color: #c6b598">
                  <i class="fa-solid fa-people-group" style="color:#141414"></i>
                </div>
                <div class="text-center">Adicionar equipe</div>
              </div>
            </div>

          </div>
        </div>

        <div class="container border-3 border-end-0 border-end-0 border-start-0 border-top border-border-dark-subtle mt-3 pt-3 ">

          <div class="row px-4">       
            <listTasksToMe :myUri="myUri"></listTasksToMe>
      </div>
    </div>

      </section>

      <section class="container">

        <div class="d-flex justify-content-center my-4">
          <img src="@/assets/banners/banner-exemplo.gif" alt="Banner Top" class="text-center img-fluid"
            style="max-width: 730px;">
        </div>

      </section>

    </template>
  </templateView>
</template>

<script>
import MyAlert from '@/services/MyAlert';
import { defineAsyncComponent } from 'vue';
import { mapActions } from 'vuex';

export default {
  name: 'homeView',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
    listTasksToMe: defineAsyncComponent(() =>
      import('@/components/task/dashboard/ListTasksToMe.vue')
    ),    
  },

  data() {
    return {
      myUri: 'task',
    }
  },

  mounted() {
    this.getItens();
   },

  methods: {
    ...mapActions('tennisCourt', ['setMyLocation', 'fechItemsCloseToMe']),

    ///
    async getItens() {
      return true;
    },

    ///
    async goToRoute(myRoute) {
      //this.$router.push({ name: 'user', params: { username: 'eduardo' } })
      this.$router.push({ name: myRoute })
    },

    ///
    async fechItemsCloseToMe() {
      await this.setMyLocation().then(async (response) => {

        if (!response || response?.code == 1) {
          this.geolocation = false
          var varMyAlert = MyAlert.init(); varMyAlert.alertError('Seu sistema negou suas coordenadas geográficas, digite o seu local atual, ou atualize as permissões');
          return;
        }
        console.log('fechItemsCloseToMe',response);

        this.geolocation = true;
        return this.goToRoute('tennisCourtPlayLoad');
      }).catch((r)=>{
        var varMyAlert = MyAlert.init(); varMyAlert.alertError(r);
      });
    }
  }//fim

}
</script>

<style lang="scss" scoped>
@import "@/assets/scss/style";

.btn-animate-border {
  border-color: #f7f7f7;
  background: 
    linear-gradient(red 0 0) bottom / 0% 2px no-repeat #fff;
  transition: 3s;
  background-size: 100% 2px;
}

.hero-section {
  min-height: calc(100svh - 80px);
}


</style>