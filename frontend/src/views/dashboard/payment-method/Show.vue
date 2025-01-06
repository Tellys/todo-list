<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row d-flex align-items-center justify-content-center h-100">
                        <p class="text-center">Abaixo estão listados os dados do Tipos de Pagamento.</p>

                        <h1 class="text-center">{{ items.name }} - id: {{ items.id }}</h1>

                        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                            <ul v-for="(item, i) in items" :key="i">
                                <li v-if="
                                    i !== 'id'
                                    && i !== 'name'
                                    //&& i !== 'image'
                                    && i !== 'description'
                                "> <strong>{{ i }}: </strong> {{ item }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';

export default {
    name: 'dashboardProductShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/dashboard/Template.vue')
        ),
    },
    data() {
        return {
            items: {},
            id: this.$route.params.id,
        }
    },
    mounted() {
        this.getItens();
    },
    methods: {
        async getItens() {
            const response = await Api.get('payment-method/' + this.id);
            console.log(response);
            return this.items = response.data;
        }
    }
}
</script>