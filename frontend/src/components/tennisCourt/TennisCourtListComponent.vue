<template>
  <div class="row flex-nowrap py-4">

    <!-- list items -->
    <section v-if="items?.success">

      <div class="row row-cols-1 row-cols-md-4 g-4 offset-md-1">

        <template v-for="(vDados, i) in items.data.data" :key="i">
          <div class="card m-lg-4" v-if="vDados.id">
            <h3 class="card-title font-weight-bold mb-2">{{ vDados.name }}</h3>
            <div class="card-body d-flex flex-row">
              <div class="card-text">
                <strong>{{ vDados.city }}</strong>/{{ vDados.state }}
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm p-0"><i class="bi bi-star"></i></button>
                  <button type="button" class="btn btn-sm p-0"><i class="bi bi-star"></i></button>
                  <button type="button" class="btn btn-sm p-0"><i class="bi bi-star"></i></button>
                  <button type="button" class="btn btn-sm p-0"><i class="bi bi-star"></i></button>
                  <button type="button" class="btn btn-sm p-0"><i class="bi bi-star"></i></button>
                </div>
              </div>
            </div>
            <div class="bg-image hover-overlay ripple rounded-0" data-bs-ripple-color="light">
              <img class="img-fluid" src="@/assets/images/3-quadras-de-beach-tennis.jpg" alt="Card image cap" />
              <a href="#!">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </a>
            </div>
            <div class="card-body">

              <div class="d-flex justify-content-between">
                <button class="btn btn-link link-danger p-md-1 my-1" type="button">Veja+</button>
                <div>
                  <button type="button" class="btn">
                    <i class="bi bi-share-fill" title="Compartilhar" data-bs-toggle="tooltip" data-bs-placement="top"
                      data-bs-title="Compartilhar"></i></button>
                  <button type="button" class="btn">
                    <i class="bi bi-heart" title="Favorito" data-bs-toggle="tooltip" data-bs-placement="top"
                      data-bs-title="Favorito"></i></button>
                  <!-- <i class="bi bi-heart-fill"></i> -->
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>

    </section>
    <!-- fim list items -->

    <section v-else>
      <div class="alert alert-warning" role="alert">Nenhum item para ser exibido</div>
    </section>

  </div>

  <div class="row flex-nowrap py-4 ">
    <!-- pagination -->
    <section v-if="items?.data?.links">
      <div class="d-flex justify-content-center">
        <nav aria-label="paginationTable">
          <ul class="pagination">

            <template v-if="items?.data?.links">
              <template v-for="(vLinks, iLinks) in items?.data?.links" :key="iLinks">
                <template v-if="vLinks.url">
                  <li :class="vLinks.active ? 'page-item active' : 'page-item'">
                    <a class="page-link" href="#" @click="getItens(vLinks.url)">
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
</template>

<script>
import { mapGetters } from "vuex";
import store from "@/store";

import helpers from "@/helpers/helpers.js";

export default {
  name: 'TennisCourtListComponent',
  data() {
    return {
      //items: {},
      pages: {},
      apiPath: 'tennis-court?page=1',
      actualApiPath: 'tennis-court?page=1',

      itemsToRender: {},

      register: {
        query: null,
      },
      error: "",
    }
  },
  mounted() {
    //this.searchBy();
  },
  computed: {
    ...mapGetters('tennisCourt', ['items', 'arrayFilters', 'filtersEnable']),
  },
  methods: {

    ///
    async searchBy(all = false) {
      var query = '';

      if (all) {
        this.register.query = null;
      }

      if (this.register.query) {
        query = '/' + helpers.encodeHTML(this.register.query)
      }

      store.dispatch('tennisCourt/searchItems', query);
      console.log(this.items)
      return this.items
    },

    ///
    async getItens(path = this.apiPath, param = '') {

      if (path.search(param) > 0) {
        return;
      }

      if (!param.length) {
        this.apiPath = path;
      }

      if (param.length && (path + param) == this.actualApiPath) {
        return;
      }

      this.actualApiPath = this.apiPath + param;

      store.dispatch('tennisCourt/fechItems', param);
      store.dispatch('tennisCourt/findInObject');

      console.log(this.items);
      return this.items
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

  }
}
</script>