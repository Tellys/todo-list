<template>
  <header>

    <div class="headerLine1">
      <MenuNavTop />
    </div>

    <SidebarMessages v-if="isLoggedIn"></SidebarMessages>
    <slot></slot>

  </header>

  <router-view />

</template>

<script>
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';

export default {
  name: 'TemplateTop',
  components: {
    MenuNavTop: defineAsyncComponent(() =>
      import('./MenuNavTop.vue')
    ),
    SidebarMessages: defineAsyncComponent(() =>
      import('./SidebarMessages.vue')
    ),
  },
  data() {
    return {
      isLoggedIn: false,
    }
  },
  mounted() {
    this.fnIsLoggedIn();
    //this.diffMinutes();
  },
  methods: {
    async fnIsLoggedIn() {
      return this.isLoggedIn = await Api.isLoggedIn()
    },
  },
}
</script>