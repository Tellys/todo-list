<template>
  <templateView>


    <template v-slot:slotPageComponet>
      <ListComponentVue :myUri="myUri"></ListComponentVue>
      <hr>
    </template>

    <template v-slot:slotTitlePage>
      <div class="p-2" style="background-color: silver;">
        <h1>Lista de Tarefas</h1>
        <p>Use os filtros para refinar sua pesquisa</p>
      </div>
    </template>


  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
export default {
  name: 'TaskList',
    components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/dashboard/Template.vue')
    ),
    ListComponentVue: defineAsyncComponent(() =>
      import('@/components/ListComponent.vue')
    ),
  },
  data() {
    return {
      myUri: 'task',
    }
  },
  mounted() {
  },
  methods: {
  }
}
</script>

