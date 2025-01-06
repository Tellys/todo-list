<template>
  <templateView>

    <template v-slot:slotLineOne>
    </template>

    <template v-slot:slotPageComponet>

      <section class="db-fill-home hero-section">
        <div class="container text-uppercase ">

          <!-- /// -->
          <div class="row my-3">
            <div class="col-md-8 offset-md-2 align-items-center">

              <div role="button" v-if="geolocation" @click="fechItemsCloseToMe()"
                class="d-flex justify-content-center align-items-center p-2 border border-5 rounded-pill"
                style="min-width: 80px; min-height: 80px;">
                <div class="me-2">
                  <IconFileSvg icon="quadra_location" height="40px" width="40px" bgColor="#083B54"></IconFileSvg>
                </div>

                <div class="text-center">Quadras Próximas de Você</div>

              </div>

              <div v-else id="tagFormGeolocation" class="btn-animate-border d-flex justify-content-center align-items-center p-2 border border-5 rounded-pill"
                style="min-width: 80px; min-height: 80px;">
                <div class="me-2">
                  <IconFileSvg icon="quadra_location" height="40px" width="40px" bgColor="#083B54"></IconFileSvg>
                </div>
                <SearchLocalGoogle></SearchLocalGoogle>
              </div>

            </div>

          </div>
          <!-- /// -->

          <div class="row px-4">

            <div class="col-6 col-md-4 offset-md-2" role="button" @click="goToRoute('tennisCourtPlayLoad')">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded-circle" style="background-color: #C3FF7F">
                  <IconFileSvg icon="quadra_lupa" height="40px" width="40px" bgColor="#141414"></IconFileSvg>
                </div>
                <div class="text-center">Procurar Quadras</div>
              </div>
            </div>

            <div class="col-6 col-md-4" role="button" @click="goToRoute('dashboardTennisCourtCreate')">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded-circle" style="background-color: #FF7665">
                  <IconFileSvg icon="quadra_add" height="40px" width="40px" bgColor="#141414"></IconFileSvg>
                </div>
                <div class="text-center">Cadastrar Quadras</div>
              </div>
            </div>

          </div>

          <div class="row px-4 mt-4">

            <div class="col-6 col-md-4 offset-md-2" role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded-circle" style="background-color: #FFE897">
                  <IconFileSvg icon="player_add_new" height="40px" width="40px" bgColor="#141414"></IconFileSvg>
                </div>
                <div class="text-center">Formar Time</div>
              </div>
            </div>

            <div class="col-6 col-md-4" role="button">
              <div class="d-flex flex-column flex-md-row align-items-center p-4">
                <div class="p-3 me-md-3 rounded-circle" style="background-color: #BFEBFF">
                  <IconFileSvg icon="player_trofeu_lupa" height="40px" width="40px" bgColor="#141414"></IconFileSvg>
                </div>
                <div class="text-center">Torneios</div>
              </div>
            </div>

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
    IconFileSvg: defineAsyncComponent(() =>
      import('@/components/icons/IconFileSvg.vue')
    ),
    SearchLocalGoogle: defineAsyncComponent(() =>
      import('@/components/homeDefault/searchLocalGoogle.vue')
    ),
  },

  data() {
    return {
      geolocation: true,
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

/* .xs = | Extra Small devices only (portrait phones) less than 576px */
@media (max-width: 575.98px) {
  .db-fill-home {
    background: url('@/assets/images/bg/bg-iphone.jpg') #fff no-repeat bottom right;
    background-size: contain;
  }
}

/* .sm > | Small devices and up (landscape, phones) 576px and up */
@media (min-width: 576px) and (max-width: 767.98px) {
  .db-fill-home {
    background: url('@/assets/images/bg/bg-ipad-pro.jpg') #fff no-repeat bottom right;
    background-size: contain;
  }
}

/* .md > | Medium devices and up (tablets) 768px and up */
@media (min-width: 768px) and (max-width: 991.98px) {
  .db-fill-home {
    background: url('@/assets/images/bg/bg-ipad-pro.jpg') #fff no-repeat bottom right;
    background-size: contain;
  }
}

/* .lg > | Large devices and up (desktops) 992px and up */
@media (min-width: 992px) and (max-width: 1199.98px) {
  .db-fill-home {
    background: url('@/assets/images/bg/bg-web-site.jpg') #fff no-repeat bottom right;
    background-size: contain;
  }
}

/* .xl = | Large devices only (desktops, TV) 1200px and up */
@media (min-width: 1200px) {
  .db-fill-home {
    background: url('@/assets/images/bg/bg-web-site.jpg') #fff no-repeat bottom right;
    background-size: contain;
  }
}
</style>