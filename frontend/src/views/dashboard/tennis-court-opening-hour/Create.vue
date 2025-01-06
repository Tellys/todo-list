<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Horários de Funcionamento</h1>
                        <p class="text-center">Casdastro de Horários de Funcionamento.</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">
                    <NavForFormCreateTennisCourt step="4"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="post" routeNameSuccessRedirect="refresh">
                        </DynamicForm>
                    </div>

                    <router-link class="btn btn-primary" :to="{ name: 'dashboardProductCreate', query: { tennis_court_id: tennis_court_id } }">Próximo</router-link>

                </div>
            </section>

            <div class="container py-5 h-100 bg-light">
                <hr />

                <ListMediaForTennisCourt :tennisCourtId="tennis_court_id"></ListMediaForTennisCourt>
            </div>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'TennisCourtOpeningHourCreate',
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
        ListMediaForTennisCourt: defineAsyncComponent(() =>
            import('@/components/tennisCourtOpeningHour/dashboard/ListOpeningHourToTennisCourt.vue')
        ),
    },
    data() {
        return {
            myUri: 'tennis-court-opening-hour/create',
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
