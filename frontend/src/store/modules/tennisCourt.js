import helpers from "@/helpers/helpers";
import Api from "@/services/Api";

export default {
  namespaced: true,

  // initial state
  state: () => ({
    items: [],
    itemsBkp: [],
    arrayFilters: [],
    filtersEnable: false,
    getMyLocation: null,
    myLocationDistance: null,
    btnFilterToInvolvement: null,
    btnFilterToSearch: null,
    queryBuild: {},
    registrationPhases: null,
  }),

  // getters
  getters: {
    items: state => state.items,
    itemsBkp: state => state.itemsBkp,
    itemsToRender: state => state.itemsToRender,
    arrayFilters: state => state.arrayFilters,
    filtersEnable: state => state.filtersEnable,
    getMyLocation: state => state.getMyLocation,
    myLocationDistance: state => state.myLocationDistance,
    btnFilterToInvolvement: state => state.btnFilterToInvolvement,
    btnFilterToSearch: state => state.btnFilterToSearch,
    queryBuild: state => state.queryBuild,
    registrationPhases: state => state.registrationPhases,
  },

  // actions
  actions: {
    ///
    async searchForDate(context, query) {

      if (query) {
        query = helpers.encodeHTML(query.date_time_start) + '|' + helpers.encodeHTML(query.date_time_end)
      }

      await Api.get('tennis-court-calendar/search/' + query).then((response) => {
        console.log(response);
        return
      })
    },

    ///
    async fechItems({ commit }, query = '', url = 'tennis-court?page=1') {

      /* if (getters.getMyLocation) {
        console.log('fechItems - f (!getters.getMyLocation)', [].concat(query, getters.getMyLocation))
      } */

      await Api.get(url + query).then(async (response) => {
        await commit('SET_ITEMS', response)
        await commit('SET_ITEMS_BKP', response)
      });
    },

    ///
    async searchItems({ commit, getters, dispatch }, query) {

      let myQuery = query;

      if (getters.getMyLocation) {
        myQuery = Object.assign(getters.getMyLocation, query)
        await commit('SET_MY_LOCATION', myQuery)
        return await dispatch('fechItemsCloseToMe');
      }

      await Api.get('tennis-court/search' + myQuery).then(async (response) => {
        await commit('SET_ITEMS', response);
        await commit('SET_ITEMS_BKP', response);
      });
    },

    ///
    async filterItems({ commit, getters }, query = {}) {

      let myUrlApi = 'tennis-court/filter-items';

      if (query?.pagination) {
        myUrlApi = query?.pagination;
        delete query.pagination
      }

      await commit('QUERY_BUILD', query);
      let myQuery = getters.queryBuild

      if (getters.getMyLocation) {
        myQuery = Object.assign(getters.queryBuild, getters.getMyLocation)
      }

      // add botões para demostração de existencia do filtro
      if (query?.involvement) {
        commit('BTN_FILTER_TO_INVOLVEMENT', query?.involvement)
      }

      await Api.post(myUrlApi, myQuery).then(async (response) => {

        if (response?.data?.myLocationDistance) {
          await commit('SET_MY_LOCATION_DISTANCE', response.data.myLocationDistance);
        }

        await commit('SET_ITEMS', response);
        await commit('SET_ITEMS_BKP', response);

        //console.log('SET_ITEMS', response)
        return response;
      });

      return false;
    },

    async setMyLocation({ commit }) {
      return await helpers.getGeolocation().then(async (r) => {
        if (!r) {
          return false;
        }
        await commit('SET_MY_LOCATION', r);
        return r;
      }, (error) => {
        return error;
      });
    },

    ///
    async fechItemsCloseToMe({ getters, dispatch }, query = {}) {

      // tentar o getMyLocation
      if (!getters.getMyLocation) {
        await dispatch('setMyLocation');
      }

      // caso o setMyLocation insista no return false
      if (!getters.getMyLocation) {
        return false;
      }

      return await dispatch('filterItems', query);
    },

    ///
    async findInObject({ commit, getters }, arrayFilters = null) {

      //caso o filtro seja definido na função
      if (arrayFilters != null) {
        await commit('SET_FILTERS', arrayFilters);
      }

      let r = [];
      var myItemsBkp = JSON.parse(localStorage.getItem("tennisCourtItems")); //getters.itemsBkp;
      let myReturn = myItemsBkp;

      for (const [, vItems] of Object.entries(myItemsBkp.data.data)) {
        for (const [, vFiltro] of Object.entries(getters.arrayFilters)) {
          for (const [, vVFiltro] of Object.entries(vFiltro)) {

            if (vItems[vVFiltro.table_db]) {

              if (!r[vVFiltro.table_db_fk]) {
                r[vVFiltro.table_db_fk] = []
              }

              //console.log(`[vVFiltro.table_db] = ${vVFiltro.table_db}`, vItems[vVFiltro.table_db])

              for (const [kVItems, vVItems] of Object.entries(vItems[vVFiltro.table_db])) {

                // verifica se a cahve possui o mesmo valor que foi escolhido pelo user (ex.: se quadra coberta)
                if (kVItems == vVFiltro.table_db_fk && vVItems == vVFiltro.value) {
                  r[vVFiltro.table_db_fk].push(vItems);
                }
                // para o caso de um sequencia de seleções dentro do parametro (ex.: se empresta bolinha; se wi-fi... etc )
                else if (vVItems?.constructor.name === "Object" && vVItems[vVFiltro.table_db_fk] == vVFiltro.value) {
                  r[vVFiltro.table_db_fk].push(vItems);
                }

              }
            }
          }
        }
      }

      let rListPositive = []
      for (const [, vListPositive] of Object.entries(r)) {
        rListPositive.push(vListPositive)
      }

      if (rListPositive.length) {
        r = rListPositive.reduce((a, b) => a.filter(c => b.includes(c)));
      }

      const uniqueValues = nums => [...new Set(nums)];
      r = uniqueValues(r)

      myReturn['data']['data'] = myItemsBkp.data.data;
      if (r.length) {
        myReturn['data']['data'] = r;
      }
      //caso retorne zero resultados por não haver associação com os filtros selecionados
      // neste caso o retorno é vazio mesmo
      else if (Object.entries(getters.arrayFilters).length) {
        myReturn['data']['data'] = r;
        myReturn['success'] = false;
      }

      await commit('SET_ITEMS', myReturn);
    },

    ///
    async registrationPhases({commit}){
      await Api.get('tennis-court/registration-phases').then((r)=>{

        if (!r.success) {
          return false
        }

        return commit('REGISTRATION_PHASES', r.data); // ira retornar os cadastros com pendencia
      })
    },

    ///fim
  },

  // mutations
  mutations: {

    ///
    SET_ITEMS: (state, items) => {
      state.items = items
    },

    ///
    SET_ITEMS_BKP: (state, itemsBkp) => {
      localStorage.setItem("tennisCourtItems", JSON.stringify(itemsBkp));
      state.itemsBkp = JSON.parse(localStorage.getItem("tennisCourtItems"))
    },

    ///
    SET_FILTERS: (state, arrayFilters) => {
      state.arrayFilters = arrayFilters

      //console.log('arrayFilters',arrayFilters);
      if (arrayFilters !== undefined && (arrayFilters?.length || Object.keys(arrayFilters)?.length)) {
        state.filtersEnable = true;
        return;
      }

      if (state.btnFilterToInvolvement || state.btnFilterToSearch) {
        state.filtersEnable = true;
        return;
      }

      state.filtersEnable = false;
    },

    ///
    SET_MY_LOCATION: (state, getMyLocation) => {
      localStorage.setItem("getMyLocation", JSON.stringify(getMyLocation));
      state.getMyLocation = JSON.parse(localStorage.getItem("getMyLocation"))
    },

    ///
    SET_ITEMS_TO_RENDER: (state, itemsToRender) => {
      state.itemsToRender = itemsToRender
    },

    ///
    SET_FILTERS_ENABLE: (state, v) => {
      if (state.btnFilterToInvolvement || state.btnFilterToSearch) {
        state.filtersEnable = true;
        return;
      }

      state.filtersEnable = v

    },

    ///
    SET_MY_LOCATION_DISTANCE: (state, v) => {
      state.myLocationDistance = v
    },

    ///
    BTN_FILTER_TO_INVOLVEMENT: (state, v) => {
      state.btnFilterToInvolvement = v

      if (v) {
        state.filtersEnable = true;
        return;
      }

      if (!Object.keys(state.arrayFilters)?.length && !state.btnFilterToSearch) {
        state.filtersEnable = false;
        return;
      }

    },

    ///
    BTN_FILTER_TO_SEARCH: (state, v) => {
      state.btnFilterToSearch = v
    },

    ///
    QUERY_BUILD: (state, q) => {

      // get all
      if (typeof q?.q === 'string') {

        // include btnFilterToSearch
        state.btnFilterToSearch = q.q;

        //enable show btns
        state.filtersEnable = true;

        // remove btnFilterToSearch
        if (q?.q === undefined || q.q == 'all') {
          delete state.queryBuild.q;

          state.btnFilterToSearch = null;
          q = {};
        }
      }

      //se remove
      if (q?.removeThis) {
        for (const [key,] of Object.entries(q.removeThis)) {

          if (state.queryBuild[key]) {
            delete state.queryBuild[key];
            delete q.removeThis;
          }
        }
      }

      state.queryBuild = { ...state.queryBuild, ...q };
    },

    ///
    REGISTRATION_PHASES: (state, v) => {
      state.registrationPhases = v
    },

    /// fim
  }

  /// fim
}