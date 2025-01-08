<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Editar dados da Tarefa</h1>
                        <p class="text-center">Modifique as informações da tarefa.</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="container py-5 h-100 bg-light">

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm :myUri="init()" myFormMethod="put"
                        :routeNameSuccessRedirect="{name:'dashboardTaskEdit'}"> 
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
    name: 'TaskEdit',
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
            myUri: 'task',
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