<template>

  <div class="d-flex justify-content-between align-items-center bd-highlight">

    <div class="p-2 bd-highlight">
      <button type="button" @click="$router.back()" class="btn btn-lg b-0" title="Voltar"><i
          class="bi bi-arrow-left"></i></button>
      <!-- <button type="button" class="btn btn-lg b-0 btnIconMainMenu"><i class="bi bi-list"></i></button> -->
    </div>

    <div class="p-2 bd-highlight">
      <router-link to="/">
        <picture>
          <img src="@/assets/images/logo/logo.png" width="200"
            alt="{{ `${process.env.VUE_APP_NAME}` }}" />
        </picture>
      </router-link>
    </div>

    <div class="p-2 bd-highlight">

      <div class="dropdown" v-if="isLoggedIn">
        
        <button type="button" @click="this.$router.push({ name: 'dashboardCart' })"
          class="btn btn-light border position-relative me-2">
          <i class="bi bi-cart3"></i>
          <span v-if="getTotalOfItemsIntoCart"
            class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
          </span>
        </button>

        <button type="button" class="btn btn-light border position-relative dropdown-toggle" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="bi bi-person-circle"></i>
          <span v-if="itemsNotRead?.total || !isUserVerifyEmail" class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
          </span>
        </button>

        <ul class="dropdown-menu">
          <button type="button" class="btn b-0 bntShowSidebar"><i class="bi bi-bell-fill"></i>
            <span class="badge bg-danger">{{ itemsNotRead?.total > 50 ? '50+' : itemsNotRead?.total }}</span>
          </button>

          <button v-if="!isUserVerifyEmail" title="Vericaçao pendente"
            @click.prevent="this.$store.dispatch('login/sendEmailToVerify')" type="button" class="btn b-0 "><i
              class="bi bi-person-exclamation"></i>
            <span class="badge bg-warning text-black">?</span>
          </button>

          <li><router-link class="dropdown-item" to="/dashboard"><i class="bi bi-grid-fill me-2"></i>
              Dashboard</router-link></li>

          <li><router-link class="dropdown-item" to="/dashboard-master"><i class="bi bi-grid-3x3-gap-fill me-2"></i>
              Dashboard Master</router-link></li>

          <li class="separator">
            <hr />
          </li>
          <li><router-link class="dropdown-item" :to="{ name: 'userProfile' }"><i class="bi bi-person-vcard me-2"></i>Meu Perfil</router-link></li>
          <li><router-link class="dropdown-item" :to="{ name: 'dashboardCustomerRequest' }"><i class="bi bi-cart-check-fill me-2"></i>Meus Pedidos</router-link></li>
          <li><a class="dropdown-item text-danger" href="#" title="Sair do sistema" @click.prevent="logout"><i
                class="bi bi-x-lg me-2"></i> Sair</a>
          </li>
          <li class="separator">
            <hr />
          </li>
          <li role="button" @click.prevent="filterByinvolvement({ involvement: 1 })" class="dropdown-item"><i
              class="fa fas fa-heart me-2 text-danger"></i>Meus Likes</li>
          <li role="button" @click.prevent="filterByinvolvement({ involvement: 2 })" class="dropdown-item"><i
              class="fa fas fa-bookmark me-2 text-danger"></i> Meus Saves</li>

          <template v-if="registrationPhases">
            <li class="separator">
              <hr />
            </li>
            <li><router-link class="dropdown-item" :to="{ name: 'dashboardTennisRegistrationPhaseList' }"><i
                  class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Cadastro Pendente</router-link></li>
          </template>
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
          <li><router-link to="/user/create-simple" class="dropdown-item">Incluir Novo [Simple]</router-link></li>
        </ul>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-center dropdown" v-if="isLoggedIn">
        <a class="dropdown-toggle " href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="rounded-circle shadow-4-strong" alt="avatar2" height="22" loading="lazy" :src="userAvatar" />
        </a>
        <ul class="dropdown-menu">
          <li><router-link class="dropdown-item" :to="{name: 'userProfile'}">Meu Perfil</router-link></li>
          <li><a class="dropdown-item" href="#" title="Sair do sistema" @click.prevent="logout">Sair</a></li>
        </ul>
      </li>

    </ul>
  </div>

  <slot></slot>
</template>

<script>
import Api from '@/services/Api';
import { mapActions, mapGetters } from 'vuex';

export default {
  name: 'MenuNavTop',
  emits: ['messageSidebarTrue'],
  data() {
    return {
      isLoggedIn: false,
      pageTitle: process.env.VUE_APP_NAME,
      userAvatar: localStorage.getItem('userImage'),
    }
  },
  /* computed: {
    ...mapGetters('login', ['isUserVerifyEmail']),
  }, */
  computed: {
    ...mapGetters('tennisCourt', ['registrationPhases']),
    ...mapGetters('cart', ['getTotalOfItemsIntoCart']),
    ...mapGetters('countDown', ['timeToCountDown']),
    ...mapGetters('login', ['isUserVerifyEmail']),
    ...mapGetters('messages', ['itemsNotRead']),
  },
  mounted() {
    ///
    this.init();
    this.fnIsLoggedIn();

    //console.log('registrationPhases', this.registrationPhases)
  },
  methods: {
    ...mapActions('tennisCourt', ['filterItems']),
    ...mapActions('cart', ['getItemsCart']),
    ...mapActions('login', ['logout']),

    ///
    async init() {
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

      this.getItemsCart();
    },

    ///
    async fnIsLoggedIn() {
      return this.isLoggedIn = await Api.isLoggedIn()
    },

    /* ///
    async logout() {
      if (this.isLoggedIn) {
        return await Api.logout();
      }
    }, */

    ///
    async filterByinvolvement(involvementId) {
      this.filterItems(involvementId);
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
