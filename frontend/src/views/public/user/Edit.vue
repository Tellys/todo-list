<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Editar dados</h1>
                        <p class="text-center">Modifique os dados em nossa base de dados.</p>

                        <p>Gostaria de tentar o AutoComplete via CNPJ?
                            <RouterLink :to="{ name: 'userSearchCnpj' }">Auto Complete via CNPJ</RouterLink>
                        </p>

                        <DynamicForm :myUri="myUri" myFormMethod="put" :routeNameSuccessRedirect="{name:'userShow'}"></DynamicForm>

                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'UserEdit',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm.vue')
        ),
    },
    data() {
        return {
            myUri: 'user/' + this.$route.params.id + '/edit'
        }
    },
}
</script>