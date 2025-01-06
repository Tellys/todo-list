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
import MenuNavTop from './MenuNavTop.vue';
import SidebarMessages from './SidebarMessages.vue';
import Api from '@/services/Api';

export default {
  name: 'TemplateTop',
  components: {
    MenuNavTop,
    SidebarMessages,
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