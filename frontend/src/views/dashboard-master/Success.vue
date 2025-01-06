<template>
  <templateView>
    <template v-slot:slotPageComponet>
      <div class="text-center">
        <h1>Sucesso</h1>
      </div>
    </template>
  </templateView>
</template>

<script>
// @ is an alias to /src
import templateView from './Template.vue';

export default {
  name: 'successView',
  components: {
    templateView,
  }
}
</script>
