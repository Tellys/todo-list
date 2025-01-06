<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro de Usuários</h1>
                        <p class="text-center">Casdastro de usuários do sistema.</p>

                        <p>Gostaria de tentar o AutoComplete via CNPJ?
                            <RouterLink :to="{ name: 'tennisCourtSearchCnpj' }">Auto Complete via CNPJ</RouterLink>
                        </p>

                        <DynamicForm myUri="tennis-court/create" myFormMethod="post"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>
 
<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'DashboardMasterTennisCourtCreate',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard-master/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    }
}

</script>
