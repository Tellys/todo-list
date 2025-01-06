<template>
<templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Editar de Produtos</h1>
                        <p class="text-center">Editar listagem de Produtos do sistema.</p>

                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">

                    <NavForFormCreateTennisCourt step="5"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="myUri" myFormMethod="put" routeNameSuccessRedirect="refresh"></DynamicForm>
                    </div>

                    <router-link class="btn btn-primary" :to="{ name: 'tennisCourtShow', params:{id:tennis_court_id } }">Próximo</router-link>

                </div>
            </section>
        </template>
    </templateView>
</template>


<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'CustomerRequestEdit',
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
    },
    data() {
        return {
            myUri: 'product/' + this.$route.params.id + '/edit'
        }
    },
    methods:{
        testAlert(){
            console.log('chamou a test onchange')
        }

    }
}
</script>