<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Complementos do Item</h1>
                        <p class="text-center">Casdastro Complementos do Item.</p>
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

                    <router-link class="btn btn-primary"
                        :to="{ name: 'dashboardProductCreate', params: { id: this.routeId } }">Próximo</router-link>

                </div>
            </section>

            <div class="container py-5 h-100 bg-light">
                <hr />

                <ListMediaForTennisCourt></ListMediaForTennisCourt>
            </div>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'TennisCourtOpeningHourEdit',
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
            myUri: 'tennis-court-description',
            routeId: this.$route.params.id ?? null,
        }
    },
    mounted() {
        //this.init()
    },
    methods: {
        init() {
            return `${this.myUri}/${this.routeId}/edit`;
        }
    },


}
</script>