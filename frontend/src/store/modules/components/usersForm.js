const state = () => ({
  items: [],
})

const actions = {}

const mutations = {
  SET_ITEMS(state, data) {
    state.items = data;

    console.log(state.items);
  },
}

const getters = {
  items: state => state.items,
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}

