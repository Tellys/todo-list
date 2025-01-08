<template>
  <templateView>
    <template v-slot:slotPageComponet>
      <section>
        <div class="container py-5 h-100">
          <div class="row d-flex align-items-center justify-content-center h-100">
            <h1 class="text-center">Redefinição de Senha</h1>
            <p class="text-center">Redefinição de Senha</p>

            <DynamicForm myUri="user/reset-password" :myUriToSubmit="thisMyUriToSubmit" myFormMethod="post">
            </DynamicForm>
          </div>
        </div>
      </section>
    </template>
  </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'ResetPasswordView',
  components: {
    templateView: defineAsyncComponent(() =>
      import('@/views/template/Template.vue')
    ),
    DynamicForm: defineAsyncComponent(() =>
      import('@/components/DynamicForm')
    ),
  },
  data() {
    return {
      thisMyUriToSubmit: `user/reset-password/${this.$route.params.token}/${this.$route.params.email}`
    }
  }
}

</script>
