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

                    <NavForFormCreateTennisCourt step="2"></NavForFormCreateTennisCourt>

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="put"
                        :routeNameSuccessRedirect="{ name: 'dashboardTennisCourtMediaCreate', query:{tennis_court_id:tennis_court_id}}"></DynamicForm>
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
            myUri: 'tennis-court-description',
            tennis_court_id: this.$route.params.id ?? null,
        }
    },
    mounted() {
        this.init()
    },
    methods: {
        init() {
            if (this.$route.params.id) {
                return `${this.myUri}/${this.tennis_court_id}/edit`;
            }
            return false
        }
    },


}
</script>