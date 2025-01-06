<template>
<templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Editar de Tipos de Pagamentos</h1>
                        <p class="text-center">Editar listagem de Tipos de Pagamentos do sistema.</p>

                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">
                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="myUri" myFormMethod="put" routeNameSuccessRedirect="refresh"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>


<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'ProductEdit',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    },
    data() {
        return {
            myUri: 'payment-method/' + this.$route.params.id + '/edit'
        }
    },
    methods:{
    }
}
</script>