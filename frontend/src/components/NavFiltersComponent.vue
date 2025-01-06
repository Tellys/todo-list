<template v-slot:slotPageComponet>

  <!-- first line -->
  <section class="d-flex flex-row justify-content-around align-items-center mb-2 py-2 bg-light">
    <div>
      <button type="button" class="btn btnOrder"><small><i class="bi bi-arrow-down-up me-2"></i>
          Ordenar</small></button>
    </div>
    <div class="vr"></div>
    <div>
      <div class="d-flex flex-column">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="toToday" value="toToday">
          <label class="form-check-label" for="toToday"><small>Para Hoje</small></label>
        </div>

        <div class="form-check form-switch" @click="modalDate()">
          <input class="form-check-input" type="checkbox" id="otherDate" value="toToday">
          <label class="form-check-label" for="otherDate"><small>Outra Data</small></label>
        </div>

        <!-- <div class="form-check form-switch">
        <label class="form-label" for="start">Outra Data:</label>
        <input type="date" id="start" class="form-control" :onfocus="this.max=new Date().toISOString().split('T')[0]" name="trip-start" :value="this.max=new Date().toISOString().split('T')[0]" :min="this.max=new Date().toISOString().split('T')[0]"
          max="2018-12-31" />
          </div> -->

      </div>

    </div>
    <div class="vr"></div>
    <div><button type="button" class="btn btnFilters"><small><i class="bi bi-sliders me-2"></i>Filtrar</small></button>
    </div>
  </section>
  <!-- first line fim -->

  <!-- inicio Array filters  -->
  <section v-if="filtersEnable" class="d-flex flex-row justify-content-around align-items-center mb-2 py-2 bg-light">
    <div>

      <!-- btn for involvements -->
      <template v-for="(vTableItems, kTableItems) in this.$store.getters['tennisCourtInvolvement/tableItems']"
        :key="kTableItems">
        <button v-if="vTableItems.id == btnFilterToInvolvement" type="button" class="btn btn-outline-secondary m-1"
          :id="'btnFilterToInvolvement[' + vTableItems.id + ']'"
          @click.prevent="myUnCheckedInvolvement({ removeThis: { involvement: vTableItems.id } })">
          <i :class="vTableItems.icon.name + ' text-danger me-2'"></i>
          {{ vTableItems.name }} <i class="bi bi-x ml-1" title="Remover"></i></button>
      </template>

      <!-- btn for involvements -->
      <button v-if="btnFilterToSearch" type="button" class="btn btn-outline-secondary m-1" id="btnFilterToSearch"
        @click.prevent="this.$store.dispatch('tennisCourt/filterItems', {q:'all'})">
        <i class="bi bi-search me-2"></i>
        {{ btnFilterToSearch }} <i class="bi bi-x ml-1" title="Remover"></i></button>

      <!-- btn for filters (descriptions) -->
      <template v-for="(vvArrayFilters, kkArrayFilters) in arrayFilters" :key="kkArrayFilters">
        <template v-for="(vArrayFilters, kArrayFilters) in vvArrayFilters" :key="kArrayFilters">
          <button type="button" :class="`btn btn-outline-secondary m-1  ` + vArrayFilters.table_db"
            :id="'tennisCourtType[' + vArrayFilters.value + ']'" @click.prevent="myUnChecked(vArrayFilters.id)">{{
              vArrayFilters.name }} <i class="bi bi-x ml-1" title="Remover"></i></button>
        </template>
      </template>


    </div>
  </section>
  <!-- end Array filters -->

  <!-- inicio aside -->
  <section class="overflow-y-auto ml-0 shadow-lg sidebarFilters  ">

    <div class="d-flex flex-row justify-content-between border-bottom bg-light shadow p-2">
      <button class="btn px-0 align-start "><i class="fs-4 bi-sliders"></i> Filtros</button>
      <button type="button" class="btn btn-sm text-danger btnFilters" title="Fechar"><i class="fs-4 bi-x"></i></button>
    </div>

    <div class="d-flex flex-column align-items-center align-items-sm-start min-vh-100 p-2">
      <ul class="nav nav-pills w-100" id="menu-filters">

        <li class="border-bottom w-100 align-start mb-2">
          <button data-bs-toggle="collapse" data-bs-target="#subMenuTypes" aria-expanded="true"
            aria-controls="subMenuTypes"
            class="btn bg-secondary d-flex flex-row w-100 btn-toggle text-white px-0 align-start py-3 px-1">
            Tipos de Quadras</button>

          <ul class="collapse flex-column ms-1 show mt-2 list-unstyled" id="subMenuTypes">
            <template v-for="(vTypes, iTypes) in types.data" :key="iTypes">

              <li v-if="vTypes.id > 0 && vTypes.name" @click="setTypesArray()"
                class="w-100 align-start nav-li-link p-2">
                <div class="check text-start">
                  <input class="form-check-input tennis-court-type" type="checkbox" :value="vTypes.id"
                    :id="'checkTennisCourtType[' + vTypes.id + ']'" :name="vTypes.name">
                  <label class="form-check-label " :for="'checkTennisCourtType[' + vTypes.id + ']'">
                    <span class="mx-2">{{ vTypes.name }}</span>
                    <!-- <a href="#" class="nav-link px-0"><i :class="'me-2 fa ' + vFilter?.icons?.name"></i> {{ vFilter.name }}</a> -->
                  </label>
                </div>
              </li>

            </template>
          </ul>
        </li>

        <li class="border-bottom w-100 align-start">
          <button data-bs-toggle="collapse" data-bs-target="#subMenuFilters" aria-expanded="true"
            aria-controls="subMenuFilters"
            class="btn bg-secondary d-flex flex-row w-100 btn-toggle text-white px-0 align-start py-3 px-1">Filtros</button>

          <ul class="collapse flex-column ms-1 show mt-2 list-unstyled" id="subMenuFilters">
            <template v-for="(vFilter, iFilter) in filters.data" :key="iFilter">

              <li v-if="vFilter.id > 0" @click="setTypesArray()" class="w-100 align-start nav-li-link p-2">
                <div class="check text-start">
                  <input class="form-check-input tennis-court-description" type="checkbox" :value="vFilter.id"
                    :id="'checkTennisCourtDescription[' + vFilter.id + ']'" :name="vFilter.name">
                  <label class="form-check-label " :for="'checkTennisCourtDescription[' + vFilter.id + ']'">
                    <i :class="'mx-2 fa ' + vFilter?.icon?.name"></i> {{ vFilter.name }}
                  </label>
                </div>
              </li>

            </template>
          </ul>
        </li>

        <!-- <li class=" w-100">
                <button data-bs-toggle="collapse" data-bs-target="#subMenu1" aria-expanded="true" aria-controls="subMenu1"
                  class="btn-toggle nav-link px-0 align-start ">
                  Bootstrap</button>

                <ul class="collapse nav flex-column ms-1 show" id="subMenu1">
                  <li v-for="(vFilter, iFilter) in filters.data" :key="iFilter" class="w-100 align-start">
                    <template v-if="vFilter.name">
                      <a href="#" class="nav-link px-0"><i class="bi "></i></a>
                    </template>
                  </li>
                </ul>

              </li> -->

      </ul>
    </div>

  </section>
  <!-- fim aside -->

</template>

<script>
import Api from '@/services/Api';
import store from '@/store';
import { mapGetters } from "vuex";
import Swal from 'sweetalert2';

export default {
  name: 'NavFiltersComponent',
  components: {
  },
  data() {
    return {
      filters: {},
      types: {},
      register: {},
      itemsToRender: {},
      tableItems: {},
      //myToday:
    }
  },

  computed: {
    ...mapGetters('tennisCourt', ['arrayFilters', 'items', 'filtersEnable', 'btnFilterToInvolvement', 'btnFilterToSearch']),
    //...mapGetters('tennisCourtInvolvement', ['tableItems']),
  },

  async mounted() {
    await this.getFilters();
    await this.getTypes();
    //await this.getTennisCourtInvolvementTableAll();

    var menu_btn = document.querySelectorAll(".btnFilters");
    var sidebar = document.querySelector(".sidebarFilters");

    for (let i = 0; i < menu_btn.length; i++) {
      menu_btn[i].addEventListener("click", function () {
        sidebar.classList.toggle("active-nav");
      });
    }
  },

  methods: {
    ///
    async modalDate() {

      let dateTimeStartnput;
      let dateTimeEndtnput;

      const date = Swal.fire({
        title: 'Seleciona Data e Horário',
        html: `
        <input type="date" id="date" class="swal2-input">
        <input type="time" id="time" class="swal2-input">`,
        confirmButtonText: 'Pesquisar Quadras Disponíveis',
        focusConfirm: false,
        didOpen: () => {
          const popup = Swal.getPopup();
          const date = new Date();
          //const tomorrow = (date.setDate(date.getDate() + 1)).toISOString();
          const tomorrow = new Date(date)
          tomorrow.setDate(date.getDate() + 1);
          const isoStringTomorrow = tomorrow.toISOString();

          const today = date.toISOString();

          dateTimeStartnput = popup.querySelector('#date');
          dateTimeEndtnput = popup.querySelector('#time');
          dateTimeStartnput.onkeyup = (event) => event.key === 'Enter' && Swal.clickConfirm();
          dateTimeEndtnput.onkeyup = (event) => event.key === 'Enter' && Swal.clickConfirm();

          //Swal.getInput().min = today.split("T")[0];
          document.querySelector('#date').min = today.split("T")[0];
          document.querySelector('#time').min = isoStringTomorrow.split("T")[0];
        },
        willClose: () => {
          document.querySelector('#otherDate').checked = false
        },
        preConfirm: () => {
          const date = dateTimeStartnput.value;
          const date_time_end = dateTimeEndtnput.value;
          if (!date || !date_time_end) {
            return Swal.showValidationMessage(`Seleciona data e horário`);
          }

          // let date1 = new Date(date);
          // let date2 = new Date(date_time_end);

          // if (date1.getTime() > date2.getTime()) {
          //   return Swal.showValidationMessage(`Data final deve vir após a inicial`);
          // }

          // helpers.encodeHTML(date);
          // helpers.encodeHTML(date_time_end)
          store.dispatch('tennisCourt/searchForDate', { date, date_time_end });
          return { date, date_time_end };
        },
      })

      //store.dispatch('tennisCourt/searchForDate', date);
      //console.log(date, dateTimeStartnput, dateTimeEndtnput);
      return date;
    },

    ///
    myUnChecked(item) {
      let toggleButton = document.getElementById(item);
      let r = true;

      if (toggleButton.checked == true) {
        r = false;
      }

      toggleButton.checked = r;

      return this.setTypesArray()
    },

    ///
    async setTypesArray() {

      let r = {};

      let varTennisCourtType = document.querySelectorAll('.tennis-court-type:checked');
      let varTennisCourtDescription = document.querySelectorAll('.tennis-court-description:checked');

      let tennisCourtType = [];
      for (let i = 0; i < varTennisCourtType.length; i++) {
        tennisCourtType.push({
          id: varTennisCourtType[i].id,
          name: varTennisCourtType[i].name,
          value: varTennisCourtType[i].value,
          table_db: 'tennis_court_group',
          table_db_fk: 'tennis_court_type_id',
        });
      }

      let tennisCourtDescription = [];
      for (let i = 0; i < varTennisCourtDescription.length; i++) {
        tennisCourtDescription.push({
          id: varTennisCourtDescription[i].id,
          name: varTennisCourtDescription[i].name,
          value: varTennisCourtDescription[i].value,
          table_db: 'tennis_court_description',
          table_db_fk: 'tennis_court_description_table_id',
        });
      }

      if (tennisCourtType.length) {
        r['tennis_court_group'] = tennisCourtType;
      }

      if (tennisCourtDescription.length) {
        r['tennis_court_description'] = tennisCourtDescription;
      }

      // let r = {
      //   tennis_court_group: tennisCourtType,
      //   tennis_court_description: tennisCourtDescription
      // };

      //  r['tennisCourtType'] = tennisCourtType;
      //  r['tennisCourtDescription'] = tennisCourtDescription;    

      await store.dispatch('tennisCourt/findInObject', r);
      return
    },

    /// filters
    async getFilters() {
      this.filters = await Api.get('tennis-court-description-table');
      return this.filters
    },

    /// types
    async getTypes() {
      this.types = await Api.get('tennis-court-type');
      return this.types
    },

    async myUnCheckedInvolvement(q = {}) {
      await store.dispatch('tennisCourt/filterItems', q);
      store.commit('tennisCourt/BTN_FILTER_TO_INVOLVEMENT', null)
    }
  }
}

</script>

<style lang="scss" scoped>
$wSidebar: 300px;

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

.active {
  display: block !important;
}

.inactive {
  display: none;
}

.sidebarFilters {
  width: $wSidebar;
  max-width: 99%;
  min-width: 200px;
  min-height: auto;
  height: 100%;
  position: fixed;
  right: -$wSidebar;
  top: 0;
  z-index: 999;
  //margin-left: -300px;
  background-color: #fff;
  transition: 0.5s;
}

.sidebarFilters ul li a {
  text-align: start;
}

.active-nav {
  right: 0;
}

.nav-li-link:active {
  background-color: blue;
  transition: 0.5s;
}

.nav-li-link:focus,
.nav-li-link:hover {
  background-color: #ccc;
}

.title-row {
  cursor: pointer;
}
</style>