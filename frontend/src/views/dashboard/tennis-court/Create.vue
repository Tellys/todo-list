<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro de Quadras</h1>
                        <p class="text-center">Casdastro de quadras do sistema.</p>

                        <p>Gostaria de tentar o AutoComplete via CNPJ?
                            <RouterLink :to="{ name: 'tennisCourtSearchCnpj' }">Auto Complete via CNPJ</RouterLink>
                        </p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">

                    <NavForFormCreateTennisCourt step="1"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm myUri="tennis-court/create" myFormMethod="post"
                        :routeNameSuccessRedirect="{name:'dashboardTennisCourtDescritpionCreate', queryPersonal:{tennis_court_id:'id'}}"> 
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
    name: 'TennisCourtCreate',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm.vue')
        ),
        NavForFormCreateTennisCourt: defineAsyncComponent(() =>
            import('@/components/tennisCourt/NavForFormCreateTennisCourt.vue')
        ),
    },
}

</script>
