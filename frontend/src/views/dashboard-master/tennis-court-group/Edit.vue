<template>
    <templateView>

        <template v-slot:slotTitlePage>
            <div class="p-2" style="background-color: silver;">
                <IconFileSvg icon="quadra_lupa" height="50px" width="50px" bgColor="#083B54"></IconFileSvg>
                <h1>Editar Grupos</h1>
                <p>Modifique os dados em nossa base de dados</p>
            </div>
        </template>


        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <DynamicForm :myUri="myUri" myFormMethod="put"
                            routeNameSuccessRedirect="dashboardTennisCourtGroupList"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'DashboardMasterTennisCourtEdit',
    emits: ["testalert"],
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
            myUri: 'tennis-court-group/' + this.$route.params.id + '/edit'
        }
    },
    mounted() {
    },
    methods:{
        testalert(){
            console.log('chamou a test onchange')
        }

    }

}
</script>