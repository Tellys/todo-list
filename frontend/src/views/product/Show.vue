<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <template v-if="items">
                    <div class="container py-5 h-100">
                        <div class="row d-flex align-items-center justify-content-center h-100">
                            <p class="text-center">Abaixo estão listados os dados do item.</p>

                            <h1 class="text-center">{{ items.products_default.name }} - id: {{ items.id }}</h1>

                            <div class="text-center">
                                <a v-if="items.products_default?.image" href="#"><img
                                        class="rounded shadow-4-strong" :alt="items.products_default.name"
                                        height="150" loading="lazy"
                                        :src="`${endPointStorage}${items.products_default.image}`" /></a>
                            </div>

                            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                                <ul>
                                    <li>
                                        <strong>Preço: </strong> {{ items.price }} / {{ items.products_default.unit }}
                                    </li>
                                    <li>
                                        <strong>Preço Promoção: </strong> {{ items.promo }}
                                    </li>
                                    <li>
                                        <strong>Descrição: </strong> {{ items.products_default.description }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </template>
            </section>
        </template>
    </templateView>
</template>

<!-- estou aqui definindo a pagina view p product -->

<script>
import Api from '@/services/Api';
import { defineAsyncComponent } from 'vue';


export default {
    name: 'ProductShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
    },
    data() {
        return {
            items: null,
            id: this.$route.params.id,
            endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    mounted() {
        this.getItens();
    },
    methods: {
        async getItens() {
            const response = await Api.get('product/' + this.id);
            //console.log(response);
            this.items = response.data;
            console.log(this.items);
            return
        }
    }
}
</script>