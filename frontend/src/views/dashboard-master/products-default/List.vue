<template>
  <templateView>


    <template v-slot:slotPageComponet>
      <ListComponentVue :myUri="myUri"></ListComponentVue>
      <hr>
    </template>

    <template v-slot:slotTitlePage>
      <div class="p-2" style="background-color: silver;">
        <IconFileSvg icon="quadra_lupa" height="50px" width="50px" bgColor="#083B54"></IconFileSvg>
        <h1>Lista de Produtos Padrão</h1>
        <p>Lista de Produtos Padrão</p>
      </div>
    </template>


  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
export default {
  name: 'ProductDefaultList',
    components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/dashboard-master/Template.vue')
    ),
    IconFileSvg: defineAsyncComponent(() =>
      import('@/components/icons/IconFileSvg.vue')
    ),
    ListComponentVue: defineAsyncComponent(() =>
      import('@/components/ListComponent.vue')
    ),
  },
  data() {
    return {
      myUri: 'products-default',
    }
  },
  mounted() {
  },
  methods: {
  }
}
</script>

