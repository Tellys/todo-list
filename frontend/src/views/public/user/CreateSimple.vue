<template>

    <LoaderComponent :is-visible="isLoading"></LoaderComponent>
    <section class="container-fluid min-vh-100 bgFull">

        <div class="d-flex align-items-center flex-column justify-content-center min-vh-100 ">
            <div class="p-2">
                <router-link to="/">
                    <picture>
                        <img src="@/assets/images/logo/logo.png" width="150"
                            alt="{{ `${process.env.VUE_APP_NAME}` }}" />
                    </picture>
                </router-link>
            </div>

            <div class="mt-auto col-md-7 col-lg-5">
                <div class="bg-white p-4 rounded-top shadow-lg ">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastre-se aa</h1>
                        <p class="text-center">Casdastre-se para ter acesso aos conteúdos.</p>

                        <DynamicForm myUri="user/create-simple"></DynamicForm>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'UserCreate',
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
    background: url('@/assets/images/bg/new-user.png') #ffffff no-repeat right center;
    background-size: contain;
}
</style>
