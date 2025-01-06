<template>
  <!-- involvement -->

  <div>
    <button :id="'involvement-share' + item.id" type="button" class="btn" @click.prevent="shareItem('involvement-share' + item.id)"
    title="Compartilhar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Compartilhar">
      <i class="bi bi-share-fill"></i></button>

    <template v-for="(vGetInvolvementTable, iGetInvolvementTable) in tableItems" :key="iGetInvolvementTable">

      <button :title="vGetInvolvementTable.description" data-bs-toggle="tooltip" data-bs-placement="top" :data-bs-title="vGetInvolvementTable.name"
      type="button" class="btn"
        @click.prevent="updateOrCreate({ tennis_court_id: item.id, tennis_court_involvement_table_id: vGetInvolvementTable.id })">
        <i :id="'involvement-' + vGetInvolvementTable.name + item.id"
          :class="isActive(item.id, vGetInvolvementTable.id) ? 'fa ' + vGetInvolvementTable.icon.name + ' involvementIconColorActive' : 'fa ' + vGetInvolvementTable.icon.name"
          ></i></button>

    </template>

  </div>
  <!-- involvement -->
</template>
<!-- 
  parei na parte de atualização do involvimento
 -->

<script>
import helpers from '@/helpers/helpers';
import store from '@/store';
import { mapGetters } from 'vuex';
import { Tooltip } from 'bootstrap'


export default {
  name: 'TennisCourtInvolvementComponent',
  data() {
    return {
    }
  },
  props: {
    item: [Object, Array],
  },
  mounted() {
    this.init();

    new Tooltip(document.body, {
      selector: "[data-bs-toggle='tooltip']",
    });
  },
  computed: {
    ...mapGetters('tennisCourtInvolvement', ['items', 'tableItems', 'itemsForUserId', 'enableRequestGetTableAll', 'enableRequestGetAllForUserId']),
  },
  methods: {

    ///
    isActive(tennis_court_id, tennis_court_involvement_table_id) {
      let item = this.itemsForUserId;
      for (var i = 0, len = item?.length; i < len; i++) {
        if (item[i]['tennis_court_id'] === tennis_court_id && item[i]['tennis_court_involvement_table_id'] === tennis_court_involvement_table_id) {
          return true;
        }
      }
      return false;
    },

    ///
    init() {
      if (this.enableRequestGetTableAll) {
        store.dispatch('tennisCourtInvolvement/getTableAll');
        store.commit('tennisCourtInvolvement/ENABLE_REQUEST_GET_TABLE_ALL', false)
      }

      if (this.enableRequestGetAllForUserId) {
        store.dispatch('tennisCourtInvolvement/getAllForUserId');
        store.commit('tennisCourtInvolvement/ENABLE_REQUEST_GET_ALL_FOR_USER_ID', false)
      }
    },

    ///
    updateOrCreate(data) {
      return store.dispatch('tennisCourtInvolvement/updateOrCreate', data);
    },

    ///
    async shareItem() {

      //let b = document.querySelector('#involvement-share' + this.item.id);

      try {
        await helpers.copyToClipboard(window.location.host + '/tennis-court/' + this.item.id).then(() => {
          // console.log(
          //   'deu', 
          //   new Tooltip(b, {title: 'Copiado!', focus:true})
          // );
        });

        await navigator?.share({
          title: this.item.name,
          text: this.item.description,
          url: '/tennis-court/'+this.item.id
        });

      } catch (error) {
        console.log('shareItem()', error);
      }
    },

  }
}
</script>

<style lang="scss" scoped>
.involvementIconColorActive {
  color: red;
}
</style>
