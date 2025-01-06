import Api from "@/services/Api";

// initial state
const state = () => ({
  items: [],
  itemsRead: [],
  itemsNotRead: [],
})

// getters
const getters = {
  items: state => state.items,
  itemsRead: state => state.itemsRead,
  itemsNotRead: state => state.itemsNotRead,
};

// actions
const actions = {

  async list({ commit }) {
    await Api.get('message-not-read').then((response) => {
      commit('SET_ITEMS_NOT_READ', response);
    })

    await Api.get('message-read').then((response) => {
      commit('SET_ITEMS_READ', response);
    }) 

    return;
  },

  ///
  updateitemInput({ commit }, e) {
    commit('SET_ITEM_DETAIL_INPUT', e);
  }
}

// mutations
const mutations = {
  SET_ITEMS: (state, items) => {
    state.items = items
  },

  SET_ITEMS_NOT_READ: (state, itemsNotRead) => {
    state.itemsNotRead = itemsNotRead
  },

  SET_ITEMS_READ: (state, itemsRead) => {
    state.itemsRead = itemsRead
  },

}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}