<template>

  <div class="d-flex justify-content-between bd-highlight">

    <div class="p-2 bd-highlight">
      <button type="button" @click="$router.back()" class="btn btn-lg b-0" title="Voltar"><i
          class="bi bi-arrow-left"></i></button>
      <button type="button" class="btn btn-lg b-0 btnIconMainMenu"><i class="bi bi-list"></i></button>
    </div>

    <div class="p-2 bd-highlight">
      <router-link to="/">
        <picture>
          <img src="@/assets/images/logo/logo.png" width="70" alt="{{ `${process.env.VUE_APP_NAME}` }}" />
        </picture>
      </router-link>
    </div>

    <div class="p-2 bd-highlight">

      <div class="dropdown" v-if="isLoggedIn">

        <a class="btn b-0 btn-lg dropdown-toggle " href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="badge bg-danger">{{ this.$store.getters['messages/itemsNotRead'].total > 50 ? '50+' :
        this.$store.getters['messages/itemsNotRead'].total }}</span>
          <span v-if="!this.$store.getters['login/isUserVerifyEmail']" class="badge bg-warning text-black">?</span>
          <img class="rounded-circle shadow-4-strong " alt="user" height="22" loading="lazy" :src="userAvatar" />
        </a>
        <ul class="dropdown-menu">
          <button type="button" class="btn b-0 bntShowSidebar"><i class="bi bi-bell-fill"></i>
            <span class="badge bg-danger">{{ this.$store.getters['messages/itemsNotRead'].total > 50 ? '50+' :
        this.$store.getters['messages/itemsNotRead'].total }}</span>
          </button>

          <button v-if="!this.$store.getters['login/isUserVerifyEmail']" title="Vericaçao pendente"
            @click.prevent="this.$store.dispatch('login/sendEmailToVerify')" type="button" class="btn b-0 "><i
              class="bi bi-person-exclamation"></i>
            <span class="badge bg-warning text-black">?</span>
          </button>

          <li><router-link class="dropdown-item" :to="{ name: 'userProfile' }">Meu Perfil</router-link></li>
          <li><a class="dropdown-item" href="#" title="Sair do sistema" @click.prevent="logout">Sair</a></li>

        </ul>
      </div>

      <router-link v-if="!isLoggedIn" to="/login">
        <button type="button" class="btn btn-lg b-0 "><i class="bi bi-person"></i></button>
      </router-link>
    </div>

  </div>

  <div class="overflow-y-auto side-navbar active-nav ml-0 shadow-lg" id="mainMenu">

    <ul class="list-group list-group-flush text-right">

      <li class="list-group-item d-flex justify-content-between align-items-center"><router-link class="me-3 active "
          to="/">Início</router-link></li>

      <li class="list-group-item d-flex justify-content-between align-items-center">
        <router-link class="me-3 " to="/about">Sobre</router-link>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center" v-if="isLoggedIn">
        <a class="dropdown-toggle " href="#" data-bs-toggle="dropdown" aria-expanded="false">Cadastros</a>
        <ul class="dropdown-menu">
          <li><router-link to="/user/list" class="dropdown-item">Listar</router-link></li>
          <li><router-link to="/user/create" class="dropdown-item">Incluir Novo</router-link></li>
          <li><router-link to="/user/create-simple" class="dropdown-item">Incluir Novo [Simple]</router-link></li>
          <li><router-link to="/user/list" class="dropdown-item">Editar / Exluir</router-link></li>
        </ul>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-center dropdown" v-else>
        <a class="dropdown-toggle " href="#" data-bs-toggle="dropdown" aria-expanded="false">Cadastro</a>
        <ul class="dropdown-menu">
          <li><router-link to="/user/create" class="dropdown-item">Incluir Novo</router-link></li>
        </ul>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-center dropdown" v-if="isLoggedIn">
        <ul class="dropdown-menu">
          <li><router-link class="dropdown-item" :to="{ name: 'userProfile' }">Meu Perfil</router-link></li>
          <li><a class="dropdown-item" href="#" title="Sair do sistema" @click.prevent="logout">Sair</a></li>
        </ul>
      </li>

    </ul>
  </div>

  <slot></slot>
</template>

<script>

import Api from '@/services/Api';
import store from '@/store';

export default {
  name: 'MenuNavTop',
  emits: ['messageSidebarTrue'],
  data() {
    return {
      isLoggedIn: false,
      pageTitle: process.env.VUE_APP_NAME,
      userAvatar: localStorage.getItem('userImage'),
      timeToCountDown: 0,
    } 
  },
  /* computed: {
    ...mapGetters('login', ['isUserVerifyEmail']),
  }, */
  mounted() {
    ///
    var menu_btn = document.querySelectorAll(".btnIconMainMenu");
    var sidebar = document.querySelector("#mainMenu");
    for (let i = 0; i < menu_btn.length; i++) {
      menu_btn[i].addEventListener("click", function () {
        sidebar.classList.toggle("active-nav");
      });
    }

    var menu_btn_2 = document.querySelectorAll(".bntShowSidebar");
    var sidebar_2 = document.querySelector("#sidebar");
    for (let i = 0; i < menu_btn_2.length; i++) {
      menu_btn_2[i].addEventListener("click", function () {
        sidebar_2.classList.toggle("active-nav");
      });
    }

    this.fnIsLoggedIn();
    this.timeToCountDown = store.getters['countDown/timeToCountDown'];
  },
  methods: {
    async fnIsLoggedIn() {
      return this.isLoggedIn = await Api.isLoggedIn()
    },
    async logout() {
      if (this.isLoggedIn) {
        return await Api.logout();
      }
    },
  }
}
</script>

<style lang="scss" scoped>
$wSidebar: 50%;

.active {
  display: block !important;
}

.inactive {
  display: none;
}

.side-navbar {
  width: $wSidebar;
  max-width: 99%;
  min-height: auto;
  height: 100%;
  position: fixed;
  left: 0;
  top: auto;
  transition: 0.5s;
  z-index: 999;
  background-color: #f9f9f9;
}

.list-group-item {
  background-color: inherit;
}

.list-group-item a {
  color: inherit;
  text-decoration: inherit;
}

.active-nav {
  left: -$wSidebar;
}
</style>
