<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro simplificado de Quadras</h1>
                        <p class="text-center">Cadastro simplificado de Quadras.</p>

                        <p>Eperimente o <RouterLink :to="{ name: 'tennisCourtSearchCnpj' }">Auto Complete via CNPJ</RouterLink></p>

                        <DynamicForm myUri="tennis-court/create-simple" myFormMethod="post"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>
 
<script>
import { defineAsyncComponent } from 'vue';

export default { 
    name: 'TennisCourtCreateSimple',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    }
}

</script>
