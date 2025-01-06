<template>
    
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <h1 class="text-center">Cadastro de Políticas de Desconto</h1>
                        <p class="text-center">Casdastro de Políticas de Desconto do sistema.</p>

                        <DynamicForm :myUri="myUri" myFormMethod="post" :routeNameSuccessRedirect="{name:'dashboardDiscountPolicyList'}"></DynamicForm>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'DiscountPolicyCreate',
components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
        DynamicForm: defineAsyncComponent(() =>
            import('@/components/DynamicForm')
        ),
    },
    data() {
        return {
            myUri: 'discount-policy/create'
        }
    },
}

</script>
