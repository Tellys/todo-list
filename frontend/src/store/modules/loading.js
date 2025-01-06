export default {
  namespaced: true,

  state: () => ({
    isLoading: false,
  }),
  //getters: {},
  //actions: {},
  mutations: {
    setLoading(state, val) {
      state.isLoading = val;
    },
  },
};