<template>

  <templateView>

    <template v-slot:slotLineOne v-if="getMyLocation">

      <!-- Location -->
      <section class="container p-md-4">

        <form class="form" @submit.prevent="setMyLocationDistance()">
          <div class="d-flex justify-content-center align-items-center">
            <div class="me-2">
              <label for="inputmyLocationDistance" class="col-form-label">Resultados próximos a</label>
            </div>
            <div>
              <div class="input-group">
                <input name="inputmyLocationDistance" type="number" id="inputmyLocationDistance"
                  v-model="inputmyLocationDistance" required min="1" max="9999" step="1" class="form-control">
                <button class="btn border border-0 border-top border-bottom bg-white" type="submit"
                  id="btnCancelSearch">km</button>
                <button class="btn btn-secondary bl-0" type="submit" id="btnSubmitSetMyLocationDistance"><i
                    class="bi bi-search"></i></button>
              </div>
            </div>
          </div>
        </form>

        <label for="customRange1" class="sr-only">Example range</label>
        <input type="range" class="form-range" v-on:change="displaySliderValue()" :value="inputmyLocationDistance"
          id="customRange1" min="1" max="9999">

      </section>
      <!-- Fim Location -->

    </template>

    <template v-slot:slotTitlePage>
      <div style="background-color: #FFCC00;">
        <div class="d-flex justify-content-center align-items-center p-2">
          <div class="me-2">
            <IconFileSvg icon="quadra_lupa" height="50px" width="50px" bgColor="#083B54"></IconFileSvg>
          </div>
          <div>
            <h1>Quadras Disponíveis</h1>
          </div>
        </div>

        <!-- Pesquisa -->
        <section class="container p-md-4">
          <div class="col-md-6 col-lg-6 offset-md-3">
            <div class="row">
              <form class="form" @submit.prevent="filterItems()">
                <div class="input-group shadow-lg">
                  <input type="search" v-model="register.q" required class="form-control br-0"
                    placeholder="Pesquise por Nome, Cidade e etc" aria-label="Pesquise por quadras"
                    aria-describedby="pesquise-por-quadras" name="q" id="q" title="Use somente letras e números">
                  <button @click.prevent="filterItems({ q: 'all' })"
                    class="btn btn-outline-secondary border border-0 border-top border-bottom bg-white" type="button"
                    id="btnCancelSearch"><i class="bi bi-x"></i></button>
                  <button class="btn btn-secondary bl-0" type="submit" id="pesquise-por-quadras"><i
                      class="bi bi-search"></i></button>
                </div>
              </form>
            </div>
          </div>
        </section>
        <!-- Fim Pesquisa -->

      </div>
    </template>

    <template v-slot:slotPageComponet>

      <!-- aside filters -->
      <NavFiltersComponent></NavFiltersComponent>
      <!-- fim aside filters -->

      <main class="container-fluid">
        <div class="row flex-nowrap py-4">

          <!-- list items -->
          <section v-if="items?.success">

            <div class="row row-cols-1 row-cols-md-4 g-4 offset-md-1">

              <template v-for="(vDados, i) in items.data.data" :key="i">


                <div class="card m-lg-4 py-3 p-sm-1" v-if="vDados.id">

                  <div class="bg-image hover-overlay ripple rounded-0 mb-2" data-bs-ripple-color="light">
                    <router-link :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }">

                      <img class="img-fluid rounded " src="@/assets/images/3-quadras-de-beach-tennis.jpg"
                        alt="Card image cap" />

                      <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </router-link>

                  </div>

                  <h3 class="card-title font-weight-bold mb-1 fs-5 text text-center">
                    <router-link :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }">{{ vDados.name
                      }}</router-link>
                  </h3>
                  <div class="align-items-center"><i class="bi bi-geo-alt me-1"></i><strong>{{ vDados.city
                      }}</strong>/{{ vDados.state_code }}</div>
                  <div class="card-body d-flex flex-row">
                    <div class="card-text">
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-fill"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i
                            class="bi bi-star-half"></i></button>
                        <button type="button" class="btn btn-sm p-0 text-warning"><i class="bi bi-star"></i></button>
                      </div>
                    </div>
                  </div>

                  <div class="card-body">

                    <div class="d-flex flex-sm-column justify-content-between align-items-sm-center">

                      <!-- involvement -->
                      <tennisCourtInvolvementComponent :item="vDados"></tennisCourtInvolvementComponent>
                      <!-- involvement -->

                      <div>
                        <router-link class="btn btn-outline-secondary" role="button" :to="{ name: 'tennisCourtShow', params: { id: vDados.id } }"><i class="bi bi-calendar-check me-2"></i>Reservar</router-link>
                      </div>

                    </div>
                  </div>
                </div>
              </template>
            </div>

          </section>

          <section v-else>
            <div class="alert alert-warning" role="alert">Nenhum item para ser exibido</div>
          </section>
          <!-- fim list items -->

        </div>

        <div class="row flex-nowrap py-4 ">
          <!-- pagination -->
          <section v-if="items?.data?.links">
            <div class="d-flex justify-content-center">
              <nav aria-label="paginationTable">
                <ul class="pagination">

                  <template v-if="items?.data?.links">
                    <template v-for="(vLinks, iLinks) in items.data.links" :key="iLinks">
                      <template v-if="vLinks.url">
                        <li :class="vLinks.active ? 'page-item active' : 'page-item'">
                          <a class="page-link" href="#" @click="paginateItens(vLinks.url)">
                            <i class="bi bi-skip-start-fill" v-if="vLinks.label == 'Anterior'"></i>
                            <i class="bi bi-skip-end-fill" v-else-if="vLinks.label == 'Próximo'"></i>
                            <i class="bi" v-else>{{ vLinks.label }}</i>
                          </a>
                        </li>
                      </template>
                    </template>
                  </template>

                </ul>
              </nav>
            </div>
          </section>
          <!-- fim pagination -->
        </div>
      </main>


    </template>
  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

import { mapGetters } from "vuex";
import store from "@/store";

import helpers from "@/helpers/helpers.js";

export default {
  name: 'TennisCourtList',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
    IconFileSvg: defineAsyncComponent(() =>
      import('@/components/icons/IconFileSvg.vue')
    ),
    NavFiltersComponent: defineAsyncComponent(() =>
      import('@/components/NavFiltersComponent.vue')
    ),
    tennisCourtInvolvementComponent: defineAsyncComponent(() =>
      import('@/components/tennisCourt/TennisCourtInvolvementComponent.vue')
    ),
  },
  data() {
    return {
      //items: {},
      pages: {},
      apiPath: 'tennis-court?page=1',

      itemsToRender: {},
      inputmyLocationDistance: null,

      register: {
        q: null,
      },
      error: "",
    }
  },
  async mounted() {
    this.inputmyLocationDistance = this.myLocationDistance;
    this.init();
  },
  computed: {
    ...mapGetters('tennisCourt', ['items', 'arrayFilters', 'filtersEnable', 'myLocationDistance', 'getMyLocation', 'queryBuild']),
  },
  methods: {

    ///
    async init() {
      if (!this.items?.success) {
        return await this.filterItems();
      }
      //return this.searchBy();
    },

    ///
    async filterItems(q = {}) {
      if (q?.q == 'all') {
        this.register.q = null;
      }

      if (this.register.q && typeof this.register.q === 'string') {
        q = { q: helpers.encodeHTML(this.register.q) };
      }

      await store.dispatch('tennisCourt/filterItems', q).then(async () => {
        await store.dispatch('tennisCourt/findInObject');
      });
    },

    ///
    async paginateItens(path = this.apiPath) {

      //console.log('path.split', path.split(`${process.env.VUE_APP_API_URL}`))
      /* if (path == this.path) {
        return;
      } */

      await store.dispatch('tennisCourt/filterItems', { pagination: path }).then(async () => {
        await store.dispatch('tennisCourt/findInObject');
      });
    },

    ///
    async page(param) {
      console.log(param, this.$router.currentRoute.value.path);
      this.$router.push({
        path: this.$router.currentRoute.value.path,
        query: { page: param }
      });
      this.$router.go(1);
    },

    ///
    setMyLocationDistance() {
      //console.log('this.myLocationDistance', {distance:this.inputmyLocationDistance})
      //let r = { distance: this.inputmyLocationDistance };
      return this.filterItems({ distance: this.inputmyLocationDistance });
    },

    ///
    async fechItemsCloseToMe() {
      await store.dispatch('tennisCourt/setMyLocation').then(async (response) => {

        if (!response || response?.code == 1) {
          this.geolocation = false
          return;
        }

        this.geolocation = true;

        await store.dispatch('tennisCourt/fechItemsCloseToMe').then(() => {
          return this.goToRoute('tennisCourtPlayLoad');
        });
      });
    },

    ///
    displaySliderValue() {
      let eSlider = document.querySelector("#customRange1");
      this.inputmyLocationDistance = eSlider.value ?? 1
      this.setMyLocationDistance();
    },

    ///fim
  }
}

</script>

<style lang="scss" scoped>
.card a {
  text-decoration: none;
}
</style>
