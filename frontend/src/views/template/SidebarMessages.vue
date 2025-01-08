<template>
  <div class="overflow-y-auto side-navbar active-nav ml-0 shadow-lg" id="sidebar">

    <ul class="nav flex-column w-100 px-2 pb-5">
      <li class="d-flex flex-row justify-content-end my-2">
        <div class="btn-group" role="group">
          <button type="button" class="btn" @click.prevent="readAll()"><i class="bi bi-check2-all"></i></button>
          <button type="button" class="btn" @click.prevent="list()"><i class="bi bi-arrow-clockwise"></i></button>
          <button type="button" class="btn bntShowSidebar"><i class="bi bi-x"></i></button>
        </div>

      </li>

      <li v-for="(vDados, i) in itemsNotRead.data" :key="i" class="nav-link w-100 list-group">
        <template v-if="vDados.id">
          <div class="list-group-item list-group-item-action p-0 m-0">

            <div @click.prevent="showMessage(vDados.id, vDados.status)" :id="'rowNotRead[' + vDados.id + ']'"
              class="d-flex justify-content-between title-row">
              <div class="btn-group w-100" role="group" :id="'isClose' + vDados.id">
                <button type="button" class="btn btn-sm text-left">
                  <i v-if="vDados.status !== 'unread' || rowNotRead[vDados.id]" class="bi bi-envelope-open"></i>
                  <i v-else class="bi bi-envelope-fill"></i>
                  {{ vDados.name }}

                </button>
                <button type="button" class="btn btn-sm"><i class="bi bi-chevron-right"></i></button>
              </div>

              <span :id="'isOpen' + vDados.id" class="inactive">
                <button type="button" class="btn me-2">
                  <i class="bi bi-chevron-down"></i>
                </button>
              </span>
            </div>
            <div class="panel text-start" :id="'message' + vDados.id">
              <hr />
              <p class="fw-bold">id = #{{ vDados.id }}</p>
              {{ vDados.message }}
              <hr />
              <BtnDelete @click.prevent="deleteMessage(vDados.id)">Delete</BtnDelete>
            </div>
          </div>
        </template>
      </li>

      <li class="nav-link w-100 list-group ">
        <div class="list-group-item list-group-item-action bg-light">
          Mensagens Lidas
          <hr>
        </div>
      </li>

      <li v-for="(vDados, i) in itemsRead.data" :key="i" class="nav-link w-100 list-group">

        <template v-if="vDados.id">
          <div class="list-group-item list-group-item-action">

            <div @click.prevent="showMessage(vDados.id, vDados.status)"
              class="d-flex justify-content-between title-row text-black-50">

              <div class="btn-group w-100" role="group" :id="'isClose' + vDados.id">
                <button type="button" class="btn btn-sm text-left">
                  <i class="bi bi-envelope-open"></i>
                  {{ vDados.name }}

                </button>
                <!-- <button type="button" class="btn btn-sm" @click.prevent="updateToReadMessage(vDados.id)"><i class="bi bi-check2"></i></button> -->
                <button type="button" class="btn btn-sm"><i class="bi bi-check2-all text-info"></i></button>
                <button type="button" class="btn btn-sm"><i class="bi bi-chevron-right"></i></button>
                <button type="button" :id="'isOpen' + vDados.id" class="inactive btn btn-sm"><i
                    class="bi bi-chevron-right"></i></button>

              </div>
              <span :id="'isOpen' + vDados.id" class="">
                <button type="button" class="btn me-2">
                  <i class="bi bi-chevron-down"></i>
                </button>
              </span>
            </div>
            <div class="panel text-start" :id="'message' + vDados.id">
              <hr />{{ vDados.message }}
              <hr />
              <BtnDelete @click.prevent="deleteMessage(vDados.id)">Delete</BtnDelete>
            </div>
          </div>
        </template>
      </li>
    </ul>

  </div>
  <slot></slot>
</template>

<script>

import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';

export default {
  name: 'SidebarMessages',
  components: {
    BtnDelete: defineAsyncComponent(() =>
      import('@/components/buttons/BtnDelete.vue')
    ),
  },
  data() {
    return {
      messageIsOpen: [],
      rowNotRead: [],
    };
  },
  computed: {
    ...mapGetters('messages', ['items', 'itemsRead', 'itemsNotRead']),
  },
  mounted() {
    ///
    var menu_btn = document.querySelectorAll(".bntShowSidebar");
    var sidebar = document.querySelector("#sidebar");
    for (let i = 0; i < menu_btn.length; i++) {
      menu_btn[i].addEventListener("click", function () {
        sidebar.classList.toggle("active-nav");
      });
    }

  },
  methods: {
    ...mapActions('messages', ['list']),

    ///
    async init() {
      return await this.list();
    },

    ///
    async showMessage(id, status) {
      var el = document.querySelector("#message" + id);

      if (!el) {
        return;
      }

      el.classList.toggle('active');
      el.parentElement.classList.toggle('shadow');
      el.parentElement.classList.toggle('p-3');

      var iconIsOpen = document.querySelector("#isOpen" + id);
      var iconIsClose = document.querySelector("#isClose" + id);
      //var classActive = "active";
      iconIsClose.classList.toggle('inactive');
      iconIsOpen.classList.toggle('active');

      if (!el.classList.contains('active')) {
        // up read message
        await this.updateToReadMessage(id, status)
      }
    },

    ///
    async updateToReadMessage(id, status) {
      //if (status == 'unread') {
      if (status) {
        return await Api.update('message/' + id, { status: 'read', increment: 'views' }, false)
          .then(async () => {
            this.rowNotRead[id] = true;
            //await this.list();
          })
      }
      return true;
    },
    async deleteMessage(id) {
      return await Api.delete('message/' + id)
        .then(async (response) => {
          if (response != false) {
            await this.list();
          }
        })
    },
    async readAll() {
      return await Api.alertConfirm('Certeza?', 'Deseja marcar todos itens como lido?')
        .then(async (confirm) => {
          if (confirm) {
            await Api.get('message/read-all')
              .then(async (response) => {
                console.log('read all msg', response)
                if (response.success) {
                  await this.list();
                }
              });
          } else { return confirm; }
        })
    }
  }
}
</script>

<style lang="scss" scoped>
$wSidebar: 90%;

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
  $wSidebar: 90%;
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
  $wSidebar: 90%;
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
  $wSidebar: 50%;
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  $wSidebar: 25%;
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
  $wSidebar: 25%;
}

.panel {
  padding: 0 18px;
  display: none;
  overflow: hidden;
}

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
  right: 0;
  top: auto;
  z-index: 999;
  //margin-left: -300px;
  background-color: #fff;
  transition: 0.5s;
}

.nav-link:active,
.nav-link:focus,
.nav-link:hover {
  background-color: #ffffff26;
}

.active-nav {
  right: -$wSidebar;
}

.title-row {
  cursor: pointer;
}
</style>
