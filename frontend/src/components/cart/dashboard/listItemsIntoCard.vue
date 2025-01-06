<template>
    <div></div>

    <table class="table table-hover" v-if="items?.cart">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">
                    <span class="d-none d-md-block">Produto</span>
                    <span class="d-md-none">Pro</span>
                </th>
                <th scope="col">
                    <span class="d-none d-md-block">Quantidade</span>
                    <span class="d-md-none">Qtd</span>
                </th>
                <th scope="col">
                    <span class="d-none d-md-block">Preço</span>
                    <span class="d-md-none">$</span>
                </th>
                <th scope="col">
                    <span class="d-none d-md-block">Desconto</span>
                    <span class="d-md-none">Desc</span>
                </th>
                <th scope="col" v-if="enableBtnDelete"></th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(v, i) in items.cart" :key="i">
                <tr>
                    <th scope="row">{{ Number(i) < 10 ? '0' + (Number(i) + Number(1)) : i }}</th>
                    <td @click="viewDetail(v.tennis_court_calendar_id ? 'tennis_court_calendar' : 'product', v.id)"
                        role="button">{{ v.product_name }}</td>
                    <td>{{ v.qty }}</td>
                    <td>{{ v.price_promo ? `<span
                            class="text-decoration-line-through me-3">${v.price}</span><span>${v.price_promo}
                        </span>` : v.price }}</td>
                    <td>{{ v.discount ?? '0,00' }}</td>
                    <td v-if="enableBtnDelete"><button class="btn text-danger" title="Excluir Item"
                            @click.prevent="this.$store.dispatch('cart/deleteItem', v.id)"><i
                                class="bi bi-x"></i></button></td>
                </tr>
            </template>
        </tbody>
    </table>

    <div class="me-3 fs-4 text-start my-3">Total:
        <template v-if="items?.price_promo && (items.price_promo < items.price)">
            <span class="text-decoration-line-through me-3">{{ this.items.price }}</span><span>{{ items.price_promo
                }}</span>
        </template>

        <template v-else-if="items?.price">
            {{ items.price }}
        </template>

    </div>


</template>

<script>
export default {
    name: 'ListItemsIntoCard',
    data() {
        return {
            varSetIcon: false,
            register: {},
            //priceToRender: null,
        }
    },
    props: {
        type: String,
        message: String,
        items: [Array, Object],
        enableBtnDelete: null
    },
    async mounted() {
    },
    methods: {
    }
}
</script>

<style scoped lang="scss"></style>