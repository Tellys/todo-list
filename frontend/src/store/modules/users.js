//import { createStore } from "vuex";
//import axios from 'axios';
import axios from '@/axios';

const state = () => ({
  tasks: [],
  selectedTask: null,
})

const actions = {
  async fetchTasks({ commit }) {
    try {
      const response = await axios.get('user');
      commit('SET_TASKS', response.data);
      console.log(response.data);
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async showTasks({ commit }, taskId) {
    try {
      const response = await axios.get(`user/${taskId}`);
      commit('SET_SELECTED_TASK', response.data);
      //console.log(response.data);
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async addTask({ commit }, newTask) {
    try {
      const response = await axios.post('user', newTask);
      commit('ADD_TASK', response.data);
      console.log(response.data);
    } catch (error) {
      console.error('Error adding task:', error);
    }
  },

  async deleteTask({ commit }, taskId) {
    try {
      const response = await axios.delete(`user/${taskId}`);
      commit('DELETE_TASK', taskId);
      console.log(response.data);
    } catch (error) {
      console.error('Error deleting task:', error);
    }
  },
  async updateTask({ commit }, { id, title }) {
    try {
      console.log('titre' + title);
      const response = await axios.put(`user/${id}`, { 'title': title });

      console.log(response.data);
      commit('UPDATE_TASK', { id, title: response.data.title });
      console.log(response.data);
    } catch (error) {
      console.error('Error updating task:', error);
    }
  },
}

const mutations = {
  SET_TASKS(state, tasks) {
    state.tasks = tasks;
  },
  ADD_TASK(state, task) {
    state.tasks.push(task);
  },
  DELETE_TASK(state, taskId) {
    state.tasks = state.tasks.filter((task) => task.id !== taskId);
  },
  SET_SELECTED_TASK(state, task) { // Define the SET_SELECTED_TASK mutation
    state.selectedTask = task;
  },
  UPDATE_TASK(state, { id, title }) {
    const taskIndex = state.tasks.findIndex((task) => task.id === id);
    if (taskIndex !== -1) {
      state.tasks[taskIndex].title = title;
      state.selectedTask = null;
    }
  },
}

const getters = {
  tasks: state => state.tasks,
  selectedTask: state => state.selectedTask,
  /* product: state => state.product,
  isLoading: state => state.isLoading,
  isCreating: state => state.isCreating,
  isUpdating: state => state.isUpdating,
  createdData: state => state.createdData,
  updatedData: state => state.updatedData,

  isDeleting: state => state.isDeleting,
  deletedData: state => state.deletedData */
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}

