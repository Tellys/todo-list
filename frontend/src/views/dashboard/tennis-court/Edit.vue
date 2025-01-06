<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Editar dados de quadra</h1>
                        <p class="text-center">Modifique os dados em nossa base de dados.</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">

                    <NavForFormCreateTennisCourt step="1"></NavForFormCreateTennisCourt>

                    <p>Gostaria de tentar o AutoComplete via CNPJ?
                        <RouterLink :to="{ name: 'tennisCourtSearchCnpj' }">Auto Complete via CNPJ</RouterLink>
                    </p>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="put"
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
    name: 'TennisCourtEdit',
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
    data() {
        return {
            myUri: 'tennis-court',
            routeId: this.$route.params.id ?? null,
        }
    },
    mounted() {
        //this.init()
    },
    methods: {
        init() {
            if (this.$route.params?.id) {
                return `${this.myUri}/${this.routeId}/edit`;
            }
            return false
        }
    },
}
</script>