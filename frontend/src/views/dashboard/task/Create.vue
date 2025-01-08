<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100 bg-light">

                    <div class="row d-flex align-items-center justify-content-center h-100 ">
                        <DynamicForm myUri="task/create" myFormMethod="post"
                        :routeNameSuccessRedirect="{name:'home'}"> 
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
    name: 'TaskCreate',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm.vue')
        ),
    },
}

</script>
