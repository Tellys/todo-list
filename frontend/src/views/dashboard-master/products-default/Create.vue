<template>
    
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro de Produtos Padrão</h1>
                        <p class="text-center">Cadastro de Produtos Padrão.</p>

                        <DynamicForm :myUri="myUri" myFormMethod="post" :routeNameSuccessRedirect="{name:'dashboardMasterProductDefaultList'}"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'ProductDefaultCreate',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard-master/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    },
    data() {
        return {
            myUri: 'products-default/create'
        }
    },
}

</script>
