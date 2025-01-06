import Api from "@/services/Api";

export default {
  namespaced: true,

  // initial state
  state: () => ({
    items: [],
    tableItems: [],
    itemsForUserId: [],
    enableRequestGetTableAll: true,
    enableRequestGetAllForUserId: true,
  }),

  // getters
  getters: {
    items: state => state.items,
    tableItems: state => state.tableItems,
    itemsForUserId: state => state.itemsForUserId,
    enableRequestGetTableAll: state => state.enableRequestGetTableAll,
    enableRequestGetAllForUserId: state => state.enableRequestGetAllForUserId,
  },

  // actions
  actions: {
    ///
    async getTableAll({ commit }) {
      await Api.get('tennis-court-involvement-table/all').then(async(response) => {
        await commit('SET_TABLE_ITEMS', response.data);
        return;
      });
    },

    ///
    getAllForUserId({ commit }) {
      Api.get('tennis-court-involvement/all-for-user-logged').then((response) => {
        commit('SET_ITEMS_FOR_USER_ID', response.data);
        return;
      });
    },

    ///
    updateOrCreate({ dispatch }, data) {
      Api.post('tennis-court-involvement/update-or-create', data).then(() => {
        return dispatch('getAllForUserId');
      });
    },

    ///fim
  },

  // mutations
  mutations: {
    SET_ITEMS: (state, items) => {
      state.items = items
    },

    SET_TABLE_ITEMS: (state, items) => {
      state.tableItems = items
    },

    SET_ITEMS_FOR_USER_ID: (state, items) => {
      state.itemsForUserId = items
    },

    ENABLE_REQUEST_GET_TABLE_ALL: (state, items) => {
      state.enableRequestGetTableAll = items
    },

    ENABLE_REQUEST_GET_ALL_FOR_USER_ID: (state, items) => {
      state.enableRequestGetAllForUserId = items
    },


  }

  /// fim
}