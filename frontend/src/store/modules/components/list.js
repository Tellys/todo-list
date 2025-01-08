// import { GET_ALL_ITEMS } from "./Types";
import axios from 'axios';

// initial state
const state = () => ({
  items: [],
  itemsPaginatedData: null,
  item: null,
  isLoading: false,
  isCreating: false,
  createdData: null,
  isUpdating: false,
  updatedData: null,
  isDeleting: false,
  deletedData: null
})

// getters
const getters = {
  itemList: state => state.items,
  itemsPaginatedData: state => state.itemsPaginatedData,
  item: state => state.item,
  isLoading: state => state.isLoading,
  isCreating: state => state.isCreating,
  isUpdating: state => state.isUpdating,
  createdData: state => state.createdData,
  updatedData: state => state.updatedData,

  isDeleting: state => state.isDeleting,
  deletedData: state => state.deletedData
};

// actions
const actions = {
  async fetchAllitems({ commit }, query = null) {
    let page = 1;
    let search = '';
    if(query !== null){
      page = query?.page || 1;
      search = query?.search || '';
    }

    commit('setItemIsLoading', true);
    let url = `${process.env.VUE_APP_API_URL}items?page=${page}`;
    if (search === null) {
      url = `${url}?page=${page}`;
    } else {
      url = `${process.env.VUE_APP_API_URL}items/view/search?search=${search}&page=${page}`
    }

    await axios.get(url)
      .then(res => {
        const items = res.data.data.data;
        commit('setItems', items);
        const pagination = {
          total: res.data.data.total,  // total number of elements or items
          per_page: res.data.data.per_page, // items per page
          current_page: res.data.data.current_page, // current page (it will be automatically updated when users clicks on some page number).
          total_pages: res.data.data.last_page // total pages in record
        }
        res.data.data.pagination = pagination;
        commit('setItemsPaginated', res.data.data);
        commit('setItemIsLoading', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsLoading', false);
      });
  },

  async fetchDetailitem({ commit }, id) {
    commit('setItemIsLoading', true);
    await axios.get(`${process.env.VUE_APP_API_URL}items/${id}`)
      .then(res => {
        commit('setItemDetail', res.data.data);
        commit('setItemIsLoading', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsLoading', false);
      });
  },

  async storeitem({ commit }, item) {
    commit('setItemIsCreating', true);
    await axios.post(`${process.env.VUE_APP_API_URL}items`, item)
      .then(res => {
        commit('saveNewitems', res.data.data);
        commit('setItemIsCreating', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsCreating', false);
      });
  },

  async updateitem({ commit }, item) {
    commit('setItemIsUpdating', true);
    commit('setItemIsUpdating', true);
    await axios.post(`${process.env.VUE_APP_API_URL}items/${item.id}?_method=PUT`, item)
      .then(res => {
        commit('saveUpdateditem', res.data.data);
        commit('setItemIsUpdating', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsUpdating', false);
      });
  },


  async deleteitem({ commit }, id) {
    commit('setItemIsDeleting', true);
    await axios.delete(`${process.env.VUE_APP_API_URL}items/${id}`)
      .then(res => {
        commit('setDeleteitem', res.data.data.id);
        commit('setItemIsDeleting', false);
      }).catch(err => {
        console.log('error', err);
        commit('setItemIsDeleting', false);
      });
  },

  updateitemInput({ commit }, e) {
    commit('setItemDetailInput', e);
  }
}

// mutations
const mutations = {
  setItems: (state, items) => {
    state.items = items
  },

  setItemsPaginated: (state, itemsPaginatedData) => {
    state.itemsPaginatedData = itemsPaginatedData
  },

  setItemDetail: (state, item) => {
    state.item = item
  },

  setDeleteitem: (state, id) => {
    state.itemsPaginatedData.data.filter(x => x.id !== id);
  },

  setItemDetailInput: (state, e) => {
    let item = state.item;
    item[e.target.name] = e.target.value;
    state.item = item
  },

  saveNewitems: (state, item) => {
    state.items.unshift(item)
    state.createdData = item;
  },

  saveUpdateditem: (state, item) => {
    state.items.unshift(item)
    state.updatedData = item;
  },

  setItemIsLoading(state, isLoading) {
    state.isLoading = isLoading
  },

  setItemIsCreating(state, isCreating) {
    state.isCreating = isCreating
  },

  setItemIsUpdating(state, isUpdating) {
    state.isUpdating = isUpdating
  },

  setItemIsDeleting(state, isDeleting) {
    state.isDeleting = isDeleting
  },

}

export default {
  // namespaced: true,
  state,
  getters,
  actions,
  mutations
}