import helpers from "@/helpers/helpers";
import Api from "@/services/Api";

// initial state
const state = () => ({
  items: [],
  itemsBkp: [],
  arrayFilters: [],
  filtersEnable: false,
  setMyLocation: null,
})

// getters
const getters = {
  items: state => state.items,
  itemsBkp: state => state.itemsBkp,
  itemsToRender: state => state.itemsToRender,
  arrayFilters: state => state.arrayFilters,
  filtersEnable: state => state.filtersEnable,
  setMyLocation: state => state.setMyLocation,
  queryBuilder(state) {
    state.setMyLocation
  },
};

// actions
const actions = {
  ///
  async searchForDate(context, query) {

    console.log('query', query);

    if (query) {
      query = helpers.encodeHTML(query.date_time_start) + '|' + helpers.encodeHTML(query.date_time_end)
    }

    await Api.get('tennis-court-calendar/search/' + query).then((response) => {
      console.log(response);
      return
    })
  },

  ///
  async fechItems({ commit }, query) {

    console.log('getters.setMyLocation', getters.setMyLocation);

    if (getters.setMyLocation) {
      console.log('fechItems - f (!getters.setMyLocation)', [].concat(query, getters.setMyLocation))
    }

    await Api.get('tennis-court/search' + query).then((response) => {
      commit('SET_ITEMS', response)
      commit('SET_ITEMS_BKP', response)
    });
  },

  ///
  async searchItems({ commit }, query) {

    console.log('getters.setMyLocation', getters.setMyLocation);

    if (getters.setMyLocation) {
      console.log('fechItems - f (!getters.setMyLocation)', [].concat(query, getters.setMyLocation))
    }

    await Api.get('tennis-court/search' + query).then((response) => {
      commit('SET_ITEMS', response);
      commit('SET_ITEMS_BKP', response);
    });
  },

  async setMyLocation({ commit }) {
    await helpers.getGeolocation().then(async (r) => {
      if (!r) {
        return false;
      }
      commit('tennisCourt/SET_MY_LOCATION', r);
      console.log('tennisCourt/SET_MY_LOCATION', r)
    }, (error) => {
      return error;
    });
  },

  ///
  async fechItemsCloseToMe({ commit, getters }) {

    const myLocation = getters.setMyLocation;

    if (!myLocation) {
      
      await helpers.getGeolocation().then(async (r) => {
        if (!r) {
          return false;
        }
        commit('tennisCourt/SET_MY_LOCATION', r);
        console.log('tennisCourt/SET_MY_LOCATION', r)
      }, (error) => {
        return error;
      });
    }

    await Api.post('tennis-court/close-to-me', myLocation).then((response) => {
      commit('SET_ITEMS', response);
      commit('SET_ITEMS_BKP', response);
      return response;
    });
  },

  ///
  findInObject({ commit, getters }) {
    console.log('findInObject inicio');
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
      console.log('vListPositive', vListPositive.length)
      rListPositive.push(vListPositive)
    }

    console.log('Object.keys(rListPositive)', rListPositive.length)

    if (rListPositive.length) {
      r = rListPositive.reduce((a, b) => a.filter(c => b.includes(c)));
    }

    const uniqueValues = nums => [...new Set(nums)];
    r = uniqueValues(r)

    myReturn['data']['data'] = myItemsBkp.data.data;
    if (r.length) {
      console.log('*-*-*-*-*-  r.length dentro', r, r.length)
      myReturn['data']['data'] = r;
    }

    commit('SET_ITEMS', myReturn);

    console.log('getters.itemsBkp', getters.itemsBkp.data.data.length)
    console.log('getters.items', getters.items.data.data.length)
  },
}

// mutations
const mutations = {
  SET_ITEMS: (state, items) => {
    state.items = items
  },

  SET_ITEMS_BKP: (state, itemsBkp) => {
    localStorage.setItem("tennisCourtItems", JSON.stringify(itemsBkp));
    state.itemsBkp = JSON.parse(localStorage.getItem("tennisCourtItems"))
  },

  SET_FILTERS: (state, arrayFilters) => {
    state.arrayFilters = arrayFilters

    state.filtersEnable = false;
    if (arrayFilters.length || Object.keys(arrayFilters).length) {
      state.filtersEnable = true;
    }
  },

  SET_MY_LOCATION: (state, setMyLocation) => {
    localStorage.setItem("setMyLocation", JSON.stringify(setMyLocation));
    state.setMyLocation = JSON.parse(localStorage.getItem("setMyLocation"))
  },

  SET_ITEMS_TO_RENDER: (state, itemsToRender) => {
    state.itemsToRender = itemsToRender
  },

  SET_FILTERS_ENABLE: (state, filtersEnable) => {
    state.filtersEnable = filtersEnable
  },

}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}