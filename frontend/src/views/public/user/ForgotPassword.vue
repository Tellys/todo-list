<template>

    <LoaderComponent :is-visible="isLoading"></LoaderComponent>
    <section class="container-fluid min-vh-100 bgFull">

        <div class="d-flex align-items-center flex-column justify-content-center min-vh-100 ">
            <div class="p-2">
                <router-link to="/">
                    <picture>
                        <img src="@/assets/images/logo-beach-tennis[horizontal].png" width="150"
                            alt="{{ `${process.env.VUE_APP_NAME}` }}" />
                    </picture>
                </router-link>
            </div>


            <div class="mt-auto col-md-7 col-lg-4">
                <div class="bg-white p-4 rounded-top shadow-lg ">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Recuperação de Senha</h1>
                        <p class="text-center">Recuperação de Senha</p>

                        <DynamicForm myUri="user/forgot-password" myUriToSubmit="user/forgot-password"
                            myFormMethod="post"></DynamicForm>
                    </div>
                </div>
            </div>
        </div>
    </section>



</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'ForgotPasswordView',
    components: {
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    }
}

</script>

<style lang="scss" scoped>
@import "@/assets/scss/style";

.bgFull {
  background: url('@/assets/images/bg/forgot-password.png') #ffffff no-repeat left center;
  background-size:contain;
}
</style>