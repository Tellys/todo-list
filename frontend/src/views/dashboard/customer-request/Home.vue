<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <h1 class="w-100 border-1 border-bottom mb-4">Pedidos</h1>

                    <div class="row align-items-start">
                        <listAllCustomerRequest></listAllCustomerRequest>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
    name: 'CustomerRequestHome',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        listAllCustomerRequest: defineAsyncComponent(() =>
            import('@/components/customerRequest/listAllCustomerRequest.vue')
        ),
    },
    data() {
        return {
            //register: {},
            //id: this.$route.params.id ?? null,
            //endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    /* mounted() {
        this.init();
    },
    methods: {
        async init() {
        },
    } */
}
</script>

<style scoped lang="scss"></style>