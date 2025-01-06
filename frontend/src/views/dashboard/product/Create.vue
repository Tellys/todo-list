<template>

    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro de Produtos</h1>
                        <p class="text-center">Casdastro de Produtos do sistema.</p>

                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">

                    <NavForFormCreateTennisCourt step="5"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="post" routeNameSuccessRedirect="refresh">
                        </DynamicForm>
                    </div>

                    <router-link class="btn btn-primary"
                        :to="{ name: 'tennisCourtShow', params: { id: tennis_court_id } }">Próximo</router-link>
                </div>
            </section>

            <div class="container py-5 h-100 bg-light">
                <hr />

                <ListProductToTennisCourt :tennisCourtId="tennis_court_id"></ListProductToTennisCourt>
            </div>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'ProductCreate',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
        NavForFormCreateTennisCourt: defineAsyncComponent(() =>
            import('@/components/tennisCourt/NavForFormCreateTennisCourt.vue')
        ),
        ListProductToTennisCourt: defineAsyncComponent(() =>
            import('@/components/product/dashboard/ListProductToTennisCourt.vue')
        ),
    },
    data() {
        return {
            myUri: 'product/create',
            tennis_court_id: this.$route.query.tennis_court_id ?? null,
        }
    },
    async mounted() {
        //this.init()
    },
    methods: {
        init() {
            return `${this.myUri}?tennis_court_id=${this.tennis_court_id}`;
        }
    },

}

</script>
