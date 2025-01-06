<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Fotos do Item</h1>
                        <p class="text-center">Casdastro fotos do Item.</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">
                    <NavForFormCreateTennisCourt step="3"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="post"
                        routeNameSuccessRedirect="refresh">
                        </DynamicForm>
                    </div>

                    <router-link class="btn btn-primary" :to="{ name: 'dashboardTennisCourtOpeningHourCreate', query:{tennis_court_id:tennis_court_id } }">Próximo</router-link>

                </div>
            </section>

            <hr />

            <ListMediaForTennisCourt :tennisCourtId="tennis_court_id"></ListMediaForTennisCourt>

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
        ListMediaForTennisCourt: defineAsyncComponent(() =>
            import('@/components/tennisCourtMedia/ListMediaForTennisCourt.vue')
        ),
    },
    data() {
        return {
            myUri: 'tennis-court-media/create',
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
